<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;

class EmployeeList extends Component
{
        use WithPagination;
    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $employees = Employee::with(['branch', 'contractType'])
            ->latest()
            ->paginate(10);

        return view('livewire.employees.employee-list', [
            'employees' => $employees,
        ]);
    }
}
