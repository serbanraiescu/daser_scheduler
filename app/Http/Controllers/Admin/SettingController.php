<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        
        $licenseData = (object) [
            'status' => 'unknown',
            'days_left' => 0,
            'last_check' => 'Pending update...',
            'key' => $settings['license_key'] ?? ''
        ];

        try {
            $licenseData = app(\App\Services\LicenseService::class)->getStatus();
        } catch (\Exception $e) {
            \Log::error('Settings License Load Error: ' . $e->getMessage());
        }

        return view('admin.settings.index', compact('settings', 'licenseData'));
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
            'birthday_voucher_enabled' => 'nullable|boolean',
            'birthday_voucher_percent' => 'nullable|integer|min:1|max:100',
            'birthday_voucher_valid_days' => 'nullable|integer|min:1',
            'reactivation_enabled' => 'nullable|boolean',
            'reactivation_days_inactive' => 'nullable|integer|min:1',
            'reactivation_discount_percent' => 'nullable|integer|min:1|max:100',
            'reactivation_voucher_valid_days' => 'nullable|integer|min:1',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|string',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'nullable|email',
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
