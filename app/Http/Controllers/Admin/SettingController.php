<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'nullable|string',
            'business_email' => 'nullable|email',
            'business_phone' => 'nullable|string',
            'booking_window_start' => 'nullable|integer',
            'booking_window_end' => 'nullable|integer',
            'fidelity_points_required' => 'nullable|integer|min:1',
            'license_key' => 'nullable|string',
            'license_kill_token' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            \App\Models\Setting::setValue($key, $value);
        }

        if ($request->has('license_key')) {
            app(\App\Services\LicenseService::class)->sync();
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    public function migrate()
    {
        try {
            \Artisan::call('migrate', ['--force' => true]);
            return back()->with('success', 'Baza de date a fost actualizată cu succes. Tabelele noi au fost create.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Eroare la migrare: ' . $e->getMessage()]);
        }
    }
}
