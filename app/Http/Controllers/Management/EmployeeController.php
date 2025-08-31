<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
        public function index()
    {
        $employees = Employee::with(['branch', 'contractType', 'user'])->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'dui' => 'required|string|max:10|unique:employees,dui',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'birth_date' => 'nullable|date',
            'hire_date' => 'required|date',
            'termination_date' => 'nullable|date|after_or_equal:hire_date',
            'gender' => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'status' => 'nullable|in:active,inactive,suspended',
            'user_id' => 'nullable|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
            'contract_type_id' => 'required|exists:contract_types,id',
        ]);

        Employee::create($validated);
        return redirect()->route('employees.index')->with('success', 'Empleado creado con éxito.');
    }


    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'dui' => 'required|string|max:10|unique:employees,dui,' . $employee->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'birth_date' => 'nullable|date',
            'hire_date' => 'required|date',
            'termination_date' => 'nullable|date|after_or_equal:hire_date',
            'gender' => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'status' => 'nullable|in:active,inactive,suspended',
            'user_id' => 'nullable|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
            'contract_type_id' => 'required|exists:contract_types,id',
        ]);

        $employee->update($validated);
        return redirect()->route('employees.index')->with('success', 'Empleado actualizado con éxito.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Empleado eliminado.');
    }
}
