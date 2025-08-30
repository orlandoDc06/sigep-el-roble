<?php

namespace App\Livewire\Payrolls;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;
use App\Models\Payroll;
use App\Services\PayrollService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $month;
    public $year;
    public $half = 'first'; // 'first' | 'second'

    // mapa employee_id => payroll_detail (cuando hay payroll para el periodo)
    public $payrollDetailsMap = [];

    protected $queryString = ['search', 'month', 'year', 'half'];

    public function mount()
    {
        // control de acceso simple: adapta según el sistema de roles (Spatie, is_admin, etc)
        $user = Auth::user();
        if (! ($user && (method_exists($user, 'hasRole') ? $user->hasRole('Administrador') : ($user->is_admin ?? false)) ) ) {
            abort(403, 'Acceso no autorizado');
        }

        $now = Carbon::now();
        $this->month = $this->month ?: $now->month;
        $this->year = $this->year ?: $now->year;
        // set default half according to current day (opcional)
        $this->half = $this->half ?: ($now->day <= 15 ? 'first' : 'second');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updated($field)
    {
        // refrescar mapa si cambian periodos
        if (in_array($field, ['month', 'year', 'half'])) {
            $this->loadPayrollDetailsMap();
        }
    }

    public function loadPayrollDetailsMap()
    {
        $service = new PayrollService();
        [$start, $end] = $service->getPeriodDates((int)$this->year, (int)$this->month, $this->half);

        $payroll = Payroll::where('period_start', $start)
                    ->where('period_end', $end)
                    ->first();

        if (! $payroll) {
            $this->payrollDetailsMap = [];
            return;
        }

        // traemos los details y los keyeamos por employee_id
        $details = $payroll->details()->get()->keyBy('employee_id');
        $this->payrollDetailsMap = $details->toArray();
    }

    public function generateAll()
    {
        $service = new PayrollService();
        [$start, $end] = $service->getPeriodDates((int)$this->year, (int)$this->month, $this->half);

        // confirmamos que no exista ya una payroll en paid? Aceptamos sobreescribir/actualizar sólo si no está paid
        $existing = Payroll::where('period_start', $start)->where('period_end', $end)->first();
        if ($existing && $existing->status === 'paid') {
            session()->flash('error', 'Ya existe una planilla pagada para este periodo. No se puede regenerar.');
            return;
        }

        $payroll = $service->generateForAll($start, $end, auth()->id());
        $this->loadPayrollDetailsMap();

        session()->flash('success', 'Planilla generada para todos con estado "generated".');
    }

    public function render()
    {
        $query = Employee::query()->with('contract'); // asume relation contract para salary
        if ($this->search) {
            $query->where(function($q) {
                $q->where('first_name', 'like', "%{$this->search}%")
                  ->orWhere('last_name', 'like', "%{$this->search}%")
                  ->orWhere('dui', 'like', "%{$this->search}%");
            });
        }

        $employees = $query->orderBy('last_name')->paginate($this->perPage);

        // cargar mapa de payroll_details actual si está vacío
        if (empty($this->payrollDetailsMap)) {
            $this->loadPayrollDetailsMap();
        }

        return view('livewire.payrolls.employee-list', [
            'employees' => $employees,
        ]);
    }
}
