<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Client::query();

        if ($request->has('q')) {
            $q = $request->input('q');
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
        }

        $clients = $query->latest()->paginate(20);
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        } else {
            $validated['tags'] = [];
        }

        \App\Models\Client::create($validated);

        return redirect()->route('admin.clients.index')->with('success', 'Client created successfully.');
    }

    public function edit(\App\Models\Client $client)
    {
        $tagsString = is_array($client->tags) ? implode(', ', $client->tags) : '';
        return view('admin.clients.edit', compact('client', 'tagsString'));
    }

    public function update(Request $request, \App\Models\Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        } else {
            $validated['tags'] = [];
        }

        $client->update($validated);

        return redirect()->route('admin.clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(\App\Models\Client $client)
    {
        $client->delete();
        return redirect()->route('admin.clients.index')->with('success', 'Client deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle); // Assuming header: name,phone,email,notes,tags

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) continue;

            $tags = isset($row[4]) ? array_map('trim', explode('|', $row[4])) : [];

            \App\Models\Client::create([
                'name' => $row[0] ?? '',
                'phone' => $row[1] ?? '',
                'email' => $row[2] ?? null,
                'notes' => $row[3] ?? null,
                'tags' => $tags,
            ]);
        }

        fclose($handle);

        return redirect()->route('admin.clients.index')->with('success', 'Clients imported successfully.');
    }
}
