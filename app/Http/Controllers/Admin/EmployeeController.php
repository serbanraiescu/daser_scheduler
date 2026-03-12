<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Service;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')->with('employee')->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $services = Service::all();
        return view('admin.employees.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'services' => 'array',
            'services.*' => 'exists:services,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'role' => 'employee',
        ]);

        $employee = Employee::create([
            'user_id' => $user->id,
            'active' => true,
        ]);

        if (!empty($validated['services'])) {
            $employee->services()->sync($validated['services']);
        }

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(403);
        }
        
        $employeeData = $employee->employee;
        if (!$employeeData) {
            $employeeData = Employee::create(['user_id' => $employee->id]);
        }

        $services = Service::all();
        $employeeServices = $employeeData->services->pluck('id')->toArray();
        
        return view('admin.employees.edit', compact('employee', 'services', 'employeeServices'));
    }

    public function update(Request $request, User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'services' => 'array',
            'services.*' => 'exists:services,id',
            'active' => 'boolean',
        ]);

        $employee->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        if (!empty($validated['password'])) {
            $employee->update(['password' => bcrypt($validated['password'])]);
        }

        $employeeData = $employee->employee;
        $employeeData->update(['active' => $request->has('active')]);
        $employeeData->services()->sync($validated['services'] ?? []);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(403);
        }
        $employee->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }
}
