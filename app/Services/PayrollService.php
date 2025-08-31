<?php
namespace App\Services;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\LegalConfiguration;
use App\Models\IsrRange;
use App\Models\ExtraHour;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayrollService
{
    /**
     * Devuelve las fechas (period_start, period_end) para una quincena.
     * $half => 'first' | 'second'
     */
    public function getPeriodDates(int $year, int $month, string $half): array
    {
        $start = Carbon::create($year, $month, ($half === 'first') ? 1 : 16)->startOfDay();

        if ($half === 'first') {
            $end = Carbon::create($year, $month, 15)->endOfDay();
        } else {
            $end = Carbon::create($year, $month, Carbon::create($year, $month, 1)->daysInMonth)->endOfDay();
        }

        return [$start->toDateString(), $end->toDateString()];
    }

    /**
     * Calcula el detalle de planilla para un empleado en un periodo dado.
     * Retorna array con las claves: base_salary_period, hourly_rate, extra_pay, bonuses_total,
     * deductions_total, isss, afp, isr, net_salary, breakdown (por si quieres guardar json).
     *
     * Asunciones:
     * - salary_base en contract/employee es salario mensual.
     * - salary_for_period = salary_base * (days_in_period / 30)
     * - hourly_rate = salary_base / 30 / 8 (según tu PDF)
     * - ISSS = min(salary_for_period * isss_percentage/100, isss_max_cap)
     * - AFP = salary_for_period * afp_percentage/100
     * - ISR: se usa la lógica de tramos: fixed_fee + (taxable - min_amount) * percentage/100
     *   (taxable se define abajo; ajústalo si la normativa lo exige distinto)
     */
    public function calculateEmployeePayroll(Employee $employee, string $periodStart, string $periodEnd): array
    {
        // 1. carga configuración legal vigente para la fecha de inicio del periodo
        $legal = LegalConfiguration::where('start_date', '<=', $periodStart)
                    ->where(function($q) use ($periodStart) {
                        $q->whereNull('end_date')->orWhere('end_date', '>=', $periodStart);
                    })
                    ->where('is_active', true)
                    ->orderByDesc('id')
                    ->first();

        if (! $legal) {
            throw new \Exception("No hay configuración legal activa para la fecha: $periodStart");
        }

        $from = Carbon::parse($periodStart);
        $to = Carbon::parse($periodEnd);
        $daysInPeriod = $from->diffInDaysFiltered(function($date) { return true; }, $to) + 1; // inclusive

        // salario mensual y salario para el periodo (proporcional)
        $salaryMonthly = $employee->contract->salary_base ?? $employee->salary ?? 0;
        $salaryForPeriod = ($salaryMonthly / 30) * $daysInPeriod;

        // tarifa por hora normal
        $hourlyRate = ($salaryMonthly / 30) / 8;

        // 2. horas extra (si existen)
        $extraHours = ExtraHour::where('employee_id', $employee->id)
                    ->whereBetween('date', [$periodStart, $periodEnd])
                    ->get();

        $extraPay = $extraHours->sum(function ($eh) use ($hourlyRate) {
            // asumimos rate_multiplier guarda el factor (1.30 para 130%)
            return (float)$eh->hours * $hourlyRate * (float)$eh->rate_multiplier;
        });

        // 3. bonos: intentamos buscar relación employee->bonuses (ajusta según tu modelo)
        $bonusesTotal = 0;
        if (method_exists($employee, 'bonuses')) {
            $employee->load(['bonuses' => function($q) use ($periodStart, $periodEnd) {
                $q->where('status', 'active');
            }]);
            foreach ($employee->bonuses as $b) {
                if (isset($b->is_percentage) && $b->is_percentage) {
                    $bonusesTotal += ($b->amount / 100) * $salaryForPeriod;
                } else {
                    $bonusesTotal += $b->amount;
                }
            }
        }

        // 4. deducciones individuales
        $deductionsTotal = 0;
        if (method_exists($employee, 'deductions')) {
            $employee->load(['deductions' => function($q) {
                $q->where('status', 'active');
            }]);
            foreach ($employee->deductions as $d) {
                if (isset($d->is_percentage) && $d->is_percentage) {
                    $deductionsTotal += ($d->amount / 100) * $salaryForPeriod;
                } else {
                    $deductionsTotal += $d->amount;
                }
            }
        }

        // 5. retenciones legales
        $isss = min($salaryForPeriod * ($legal->isss_percentage / 100), (float)$legal->isss_max_cap);
        $afp = $salaryForPeriod * ($legal->afp_percentage / 100);

        // 6. ISR: calculado sobre una base imponible que definimos como:
        // taxable = salario periodo + bonos + extraPay - deducciones (ajustable según ley)
        $taxable = $salaryForPeriod + $bonusesTotal + $extraPay - $deductionsTotal;

        // obtener tramos ISR asociados a esta legal_configuration
        $ranges = IsrRange::where('legal_configuration_id', $legal->id)
                    ->orderBy('min_amount')
                    ->get();

        $isr = 0.0;
        foreach ($ranges as $r) {
            $max = $r->max_amount;
            if ( ( $taxable >= $r->min_amount ) && ( is_null($max) || $taxable <= $max ) ) {
                // fórmula: fixed_fee + percentage% sobre el excedente del min_amount
                $excess = max(0, $taxable - $r->min_amount);
                $isr = $r->fixed_fee + ($excess * ($r->percentage / 100));
                break;
            }
        }

        // 7. neto
        $gross = $salaryForPeriod + $bonusesTotal + $extraPay;
        $totalDeductions = $deductionsTotal + $isss + $afp + $isr;
        $net = $gross - $totalDeductions;

        $breakdown = [
            'salary_monthly' => round($salaryMonthly, 2),
            'salary_for_period' => round($salaryForPeriod, 2),
            'hourly_rate' => round($hourlyRate, 4),
            'extra_pay' => round($extraPay, 2),
            'bonuses_total' => round($bonusesTotal, 2),
            'deductions_total' => round($deductionsTotal, 2),
            'isss' => round($isss, 2),
            'afp' => round($afp, 2),
            'isr' => round($isr, 2),
            'gross' => round($gross, 2),
            'net' => round($net, 2),
        ];

        return $breakdown;
    }

    /**
     * Genera/crea (o actualiza) el payroll y payroll_detail para un empleado.
     * - Si no existe payroll para el periodo, lo crea (status 'generated')
     * - Crea/actualiza el payroll_detail del empleado con el desglose
     */
    public function generateForEmployee(Employee $employee, string $periodStart, string $periodEnd, int $createdById): PayrollDetail
    {
        return DB::transaction(function() use ($employee, $periodStart, $periodEnd, $createdById) {
            // 1) buscar o crear payroll (una sola planilla por periodo)
            $payroll = Payroll::firstOrCreate(
                [
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd,
                ],
                [
                    'status' => 'generated',
                    'created_by' => $createdById,
                ]
            );

            // 2) calcular
            $calc = $this->calculateEmployeePayroll($employee, $periodStart, $periodEnd);

            // 3) crear/actualizar payroll_detail
            $detail = PayrollDetail::updateOrCreate(
                [
                    'payroll_id' => $payroll->id,
                    'employee_id' => $employee->id,
                ],
                [
                    'base_salary' => $calc['salary_for_period'],
                    'bonuses_total' => $calc['bonuses_total'],
                    'extra_hours_total' => $calc['extra_pay'],
                    'deductions_total' => $calc['deductions_total'],
                    'isss' => $calc['isss'],
                    'afp' => $calc['afp'],
                    'isr' => $calc['isr'],
                    'gross' => $calc['gross'],
                    'net' => $calc['net'],
                    'status' => 'generated', // por defecto
                    'details' => json_encode($calc),
                ]
            );

            return $detail;
        });
    }

    /**
     * Genera planilla para todos los empleados activos en periodo.
     * Retorna la instancia del Payroll creado.
     */
    public function generateForAll(string $periodStart, string $periodEnd, int $createdById)
    {
        return DB::transaction(function() use ($periodStart, $periodEnd, $createdById) {
            $payroll = Payroll::firstOrCreate(
                ['period_start' => $periodStart, 'period_end' => $periodEnd],
                ['status' => 'generated', 'created_by' => $createdById]
            );

            // obtener todos los empleados activos (ajusta el scope si usas otro campo)
            $employees = \App\Models\Employee::where('status', 'active')->get();

            foreach ($employees as $emp) {
                $this->generateForEmployee($emp, $periodStart, $periodEnd, $createdById);
            }

            return $payroll;
        });
    }

}
