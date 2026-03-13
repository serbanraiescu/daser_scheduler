<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $hasTable = \Schema::hasTable('service_categories');
        $services = Service::query()->when($hasTable, function($q) {
            return $q->with('category');
        })->get();
        
        $view = view('admin.services.index', compact('services'));
        
        if (!$hasTable) {
            $view->with('error', 'Table service_categories missing. Some features may be limited.');
        }
        
        return $view;
    }

    public function create()
    {
        $categories = \Schema::hasTable('service_categories') 
            ? \App\Models\ServiceCategory::where('active', true)->get()
            : collect();
        return view('admin.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ];

        if (\Schema::hasTable('service_categories')) {
            $rules['category_id'] = 'nullable|exists:service_categories,id';
        }

        $validated = $request->validate($rules);

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $categories = \Schema::hasTable('service_categories') 
            ? \App\Models\ServiceCategory::where('active', true)->get()
            : collect();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ];

        if (\Schema::hasTable('service_categories')) {
            $rules['category_id'] = 'nullable|exists:service_categories,id';
        }

        $validated = $request->validate($rules);

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
}
