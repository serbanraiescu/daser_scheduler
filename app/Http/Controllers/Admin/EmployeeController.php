<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = \App\Models\User::where('role', 'employee')->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $services = \App\Models\Service::all();
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

        $employee = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'role' => 'employee',
        ]);

        if (!empty($validated['services'])) {
            $employee->services()->sync($validated['services']);
        }

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(\App\Models\User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(403);
        }
        $services = \App\Models\Service::all();
        $employeeServices = $employee->services->pluck('id')->toArray();
        return view('admin.employees.edit', compact('employee', 'services', 'employeeServices'));
    }

    public function update(Request $request, \App\Models\User $employee)
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
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        $employee->update($updateData);
        $employee->services()->sync($validated['services'] ?? []);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(\App\Models\User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(403);
        }
        $employee->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }
}
