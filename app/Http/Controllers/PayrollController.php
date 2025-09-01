<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\Employee;
use App\Models\SpecialDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    /**
     * Listar empleados y planillas del periodo actual
     */
    public function index(Request $request)
    {
        $today = Carbon::today();
        $start = $today->copy()->startOfMonth();
        $end   = $today->copy()->endOfMonth();

        if ($today->day <= 15) {
            $periodStart = $start;
            $periodEnd   = $start->copy()->addDays(14);
        } else {
            $periodStart = $start->copy()->addDays(15);
            $periodEnd   = $end;
        }

        $currentPeriod = [
            'start' => $periodStart->toDateString(),
            'end'   => $periodEnd->toDateString(),
        ];

        $search = $request->input('search');
        $employees = Employee::with(['branch', 'payrollDetails.payroll'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('dui', 'like', "%{$search}%");
                });
            })
            ->orderBy('first_name')
            ->paginate(10);

        return view('payrolls.index', compact('employees', 'currentPeriod'));
    }

    public function generateAll()
    {
        $today = now();
        $periodStart = $today->day <= 15
            ? $today->copy()->startOfMonth()
            : $today->copy()->startOfMonth()->addDays(15);

        $periodEnd = $today->day <= 15
            ? $today->copy()->startOfMonth()->addDays(14)
            : $today->copy()->endOfMonth();

        $employees = Employee::all();
        $generated = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            $payroll = Payroll::firstOrCreate(
                ['period_start' => $periodStart, 'period_end' => $periodEnd],
                ['status' => 'generated', 'generated_at' => now(), 'created_by' => auth()->id()]
            );

            foreach ($employees as $employee) {
                $exists = PayrollDetail::where('employee_id', $employee->id)
                    ->whereHas('payroll', fn($q) => $q->where('period_start', $periodStart)->where('period_end', $periodEnd))
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                $totales = $this->calcularTotales($employee, $periodStart, $periodEnd);

                PayrollDetail::create(array_merge([
                    'payroll_id'  => $payroll->id,
                    'employee_id' => $employee->id,
                ], $totales));

                $generated++;
            }

            DB::commit();
            return redirect()->route('payrolls.index')->with('success',
                "Planilla generada. $generated empleados nuevos, $skipped ya tenían.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    /**
     * Vista previa de generación
     */
    public function generate(Employee $employee)
    {
        $today = now();
        $periodStart = $today->day <= 15
            ? $today->copy()->startOfMonth()
            : $today->copy()->startOfMonth()->addDays(15);

        $periodEnd = $today->day <= 15
            ? $today->copy()->startOfMonth()->addDays(14)
            : $today->copy()->endOfMonth();

        $totales = $this->calcularTotales($employee, $periodStart, $periodEnd);

        // Colecciones para la vista (detalles)
        $bonuses = $employee->bonuses()
            ->wherePivot('status', 'active')
            ->wherePivotBetween('applied_at', [$periodStart, $periodEnd])
            ->get();

        $deductions = $employee->deductions()
            ->wherePivot('status', 'active')
            ->wherePivotBetween('applied_at', [$periodStart, $periodEnd])
            ->get();

        $advances = $employee->anticipos()
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->where('status', 'active')
            ->get();

        // Aliases para que la vista legacy no falle
        $aliasesParaVista = [
            'baseSalaryQuincena' => $totales['base_salary'],
            'bonusesTotal'       => $totales['bonuses_total'],
            'deductionsTotal'    => $totales['deductions_total'],
            'advancesTotal'      => $totales['advances_total'],
            'extraHoursTotal'    => $totales['extra_hours_total'],
            'isss'               => $totales['isss'],
            'afp'                => $totales['afp'],
            'isr'                => $totales['isr'],
            'netSalary'          => $totales['net_salary'],
        ];

        return view('payrolls.generate', array_merge([
            'employee'    => $employee,
            'periodStart' => $periodStart,
            'periodEnd'   => $periodEnd,
            'bonuses'     => $bonuses,
            'deductions'  => $deductions,
            'advances'    => $advances,
        ], $totales, $aliasesParaVista));
    }



    /**
     * Guardar planilla en BD
     */
    public function store(Request $request, Employee $employee)
    {
        DB::beginTransaction();
        try {
            $today = now();
            $periodStart = $today->day <= 15
                ? $today->copy()->startOfMonth()
                : $today->copy()->startOfMonth()->addDays(15);

            $periodEnd = $today->day <= 15
                ? $today->copy()->startOfMonth()->addDays(14)
                : $today->copy()->endOfMonth();

            $exists = PayrollDetail::where('employee_id', $employee->id)
                ->whereHas('payroll', fn($q) => $q->where('period_start', $periodStart)->where('period_end', $periodEnd))
                ->exists();


            if ($exists) {
                return redirect()->route('payrolls.index')
                                 ->with('error', 'Ya existe planilla para este empleado en esta quincena.');
            }

            $payroll = Payroll::firstOrCreate(
                ['period_start' => $periodStart, 'period_end' => $periodEnd],
                ['status' => 'generated', 'generated_at' => now(), 'created_by' => auth()->id()]
            );

            $totales = $this->calcularTotales($employee, $periodStart, $periodEnd);

            PayrollDetail::create(array_merge([
                'payroll_id'  => $payroll->id,
                'employee_id' => $employee->id,
            ], $totales));

            DB::commit();
            return redirect()->route('payrolls.index')->with('success', 'Planilla generada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('details.employee');
        return view('payrolls.show', compact('payroll'));
    }

    public function updateStatus(Payroll $payroll, Request $request)
    {
        $newStatus = $request->status;

        if ($payroll->status === 'generated' && $newStatus === 'approved') {
            $payroll->update([
                'status'      => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);
        } elseif ($payroll->status === 'approved' && $newStatus === 'paid') {
            $payroll->update([
                'status'   => 'paid',
                'paid_at'  => now(),
            ]);
        }

        return back()->with('success', 'Estado actualizado.');
    }


    /**
     * Calcular todos los totales de planilla
     */
    private function calcularTotales(Employee $employee, $periodStart, $periodEnd)
    {
        $baseSalary = $employee->contractType->salary_base ?? $employee->contractType->base_salary ?? 0;
        $baseSalaryQuincena = $baseSalary / 2;

        $bonusesTotal = $employee->bonuses()
            ->wherePivot('status', 'active')
            ->wherePivotBetween('applied_at', [$periodStart, $periodEnd])
            ->sum('employee_bonus_assignments.amount');

        $deductionsTotal = $employee->deductions()
            ->wherePivot('status', 'active')
            ->wherePivotBetween('applied_at', [$periodStart, $periodEnd])
            ->sum('employee_deduction_assignments.amount');

        $advancesTotal = $employee->anticipos()
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->where('status', 'active')
            ->sum('amount');

        /*$hourlyRate = ($baseSalary / 30) / 8;
        $extraHoursTotal = $employee->attendances()
            ->whereBetween('check_in_time', [$periodStart, $periodEnd])
            ->with('extraHours')
            ->get()
            ->flatMap->extraHours
            ->sum(fn($eh) => $eh->hours * $hourlyRate * $eh->rate_multiplier);*/

        $hourlyRate = ($baseSalary / 30) / 8;
        $extraHoursTotal = \App\Models\ExtraHour::where('employee_id', $employee->id)
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->sum(DB::raw("hours * $hourlyRate * rate_multiplier"));


        // === Faltas injustificadas ===
        // Contar días hábiles de lunes a sábado (0=domingo, 1=lunes, ..., 6=sábado)
        $workDays = Carbon::parse($periodStart)->diffInDaysFiltered(fn($d) => $d->dayOfWeek >= 1 && $d->dayOfWeek <= 6, $periodEnd) + 1; // Incluye el día de fin
        $attendedDays = $employee->attendances()->whereBetween('check_in_time', [$periodStart, $periodEnd])->count();
        $justifiedAbsences = $employee->justifiedAbsences()->whereBetween('date', [$periodStart, $periodEnd])->count();
        $holidays = SpecialDay::whereBetween('date', [$periodStart, $periodEnd])->count();

        $unjustifiedAbsences = max(0, $workDays - ($attendedDays + $justifiedAbsences + $holidays));
        $dailyRate = $baseSalary / 30;
        $absencePenalty = $unjustifiedAbsences * $dailyRate;

        // Retenciones
        $isss = min($baseSalaryQuincena * 0.03, 30);
        $afp  = $baseSalaryQuincena * 0.0725;
        $isr  = $this->calcularISR($baseSalaryQuincena);

        $netSalary = $baseSalaryQuincena + $bonusesTotal + $extraHoursTotal
                   - $deductionsTotal - $advancesTotal - $absencePenalty
                   - $isss - $afp - $isr;

        return [
            'base_salary'      => $baseSalaryQuincena,
            'bonuses_total'    => $bonusesTotal,
            'deductions_total' => $deductionsTotal + $absencePenalty,
            'advances_total'   => $advancesTotal,
            'extra_hours_total'=> $extraHoursTotal,
            'isss'             => $isss,
            'afp'              => $afp,
            'isr'              => $isr,
            'net_salary'       => $netSalary,
        ];
    }

    private function calcularISR($salarioQuincenal)
    {
        if ($salarioQuincenal <= 472.00) return 0;
        if ($salarioQuincenal <= 895.24) return ($salarioQuincenal - 472.00) * 0.10 + 17.67;
        if ($salarioQuincenal <= 2038.10) return ($salarioQuincenal - 895.24) * 0.20 + 60.00;
        return ($salarioQuincenal - 2038.10) * 0.30 + 288.57;
    }

        /**
     * Mostrar planilla del empleado autenticado
     */
    public function showEmployeePayroll()
    {
        $user = auth()->user();
        $employee = $user->employee;
        
        if (!$employee) {
            return redirect()->route('employee.dashboard')
                            ->with('error', 'No se encontró información de empleado asociada.');
        }

        // Calcular periodo actual
        $today = Carbon::today();
        $start = $today->copy()->startOfMonth();
        $end   = $today->copy()->endOfMonth();

        if ($today->day <= 15) {
            $periodStart = $start;
            $periodEnd   = $start->copy()->addDays(14);
        } else {
            $periodStart = $start->copy()->addDays(15);
            $periodEnd   = $end;
        }

        // Buscar planilla existente para este período
        $payrollDetail = PayrollDetail::where('employee_id', $employee->id)
            ->whereHas('payroll', function($q) use ($periodStart, $periodEnd) {
                $q->where('period_start', $periodStart)
                ->where('period_end', $periodEnd);
            })
            ->with('payroll')
            ->first();

        // Si no existe planilla, calcular datos temporales para preview
        if (!$payrollDetail) {
            $totales = $this->calcularTotales($employee, $periodStart, $periodEnd);
            
            // Crear objeto temporal para la vista
            $payrollDetail = (object) [
                'payroll' => (object) [
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd,
                    'status' => 'pending'
                ],
                'base_salary' => $totales['base_salary'],
                'bonuses_total' => $totales['bonuses_total'],
                'deductions_total' => $totales['deductions_total'],
                'advances_total' => $totales['advances_total'],
                'extra_hours_total' => $totales['extra_hours_total'],
                'isss' => $totales['isss'],
                'afp' => $totales['afp'],
                'isr' => $totales['isr'],
                'net_salary' => $totales['net_salary'],
            ];
        }

        // Obtener detalles adicionales para mostrar en la vista
        $bonuses = $employee->bonuses()
            ->wherePivot('status', 'active')
            ->wherePivotBetween('applied_at', [$periodStart, $periodEnd])
            ->get();

        $deductions = $employee->deductions()
            ->wherePivot('status', 'active')
            ->wherePivotBetween('applied_at', [$periodStart, $periodEnd])
            ->get();

        $advances = $employee->anticipos()
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->where('status', 'active')
            ->get();

        $extraHours = \App\Models\ExtraHour::where('employee_id', $employee->id)
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->get();

        return view('employee.payroll', compact(
            'employee', 
            'payrollDetail', 
            'periodStart', 
            'periodEnd',
            'bonuses',
            'deductions', 
            'advances',
            'extraHours'
        ));
    }

    /**
 * Descargar planilla del empleado autenticado en PDF
 */
public function downloadEmployeePayrollPDF()
{
    $user = auth()->user();
    $employee = $user->employee;
    
    if (!$employee) {
        return redirect()->route('employee.dashboard')
                        ->with('error', 'No se encontró información de empleado asociada.');
    }

    // Calcular periodo actual (mismo código que showEmployeePayroll)
    $today = Carbon::today();
    $start = $today->copy()->startOfMonth();
    $end   = $today->copy()->endOfMonth();

    if ($today->day <= 15) {
        $periodStart = $start;
        $periodEnd   = $start->copy()->addDays(14);
    } else {
        $periodStart = $start->copy()->addDays(15);
        $periodEnd   = $end;
    }

    // Buscar planilla existente para este período
    $payrollDetail = PayrollDetail::where('employee_id', $employee->id)
        ->whereHas('payroll', function($q) use ($periodStart, $periodEnd) {
            $q->where('period_start', $periodStart)
            ->where('period_end', $periodEnd);
        })
        ->with('payroll')
        ->first();

    // Si no existe planilla, calcular datos temporales
    if (!$payrollDetail) {
        $totales = $this->calcularTotales($employee, $periodStart, $periodEnd);
        
        $payrollDetail = (object) [
            'payroll' => (object) [
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'status' => 'pending'
            ],
            'base_salary' => $totales['base_salary'],
            'bonuses_total' => $totales['bonuses_total'],
            'deductions_total' => $totales['deductions_total'],
            'advances_total' => $totales['advances_total'],
            'extra_hours_total' => $totales['extra_hours_total'],
            'isss' => $totales['isss'],
            'afp' => $totales['afp'],
            'isr' => $totales['isr'],
            'net_salary' => $totales['net_salary'],
        ];
    }

    // Obtener detalles adicionales
    $bonuses = $employee->bonuses()
        ->wherePivot('status', 'active')
        ->wherePivotBetween('applied_at', [$periodStart, $periodEnd])
        ->get();

    $deductions = $employee->deductions()
        ->wherePivot('status', 'active')
        ->wherePivotBetween('applied_at', [$periodStart, $periodEnd])
        ->get();

    $advances = $employee->anticipos()
        ->whereBetween('date', [$periodStart, $periodEnd])
        ->where('status', 'active')
        ->get();

    // Generar PDF
    $pdf = \PDF::loadView('employee.payroll-pdf', compact(
        'employee', 
        'payrollDetail', 
        'periodStart', 
        'periodEnd',
        'bonuses',
        'deductions', 
        'advances'
    ));

    $fileName = 'planilla_' . $employee->first_name . '_' . $employee->last_name . '_' . 
                $periodStart->format('Y-m-d') . '_' . $periodEnd->format('Y-m-d') . '.pdf';

    return $pdf->download($fileName);
}
}
