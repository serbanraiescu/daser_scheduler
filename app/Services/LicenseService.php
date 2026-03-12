<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class LicenseService
{
    const API_URL = 'https://app.daserdesign.ro/api/license/verify';
    const CACHE_KEY = 'license_status_data';
    const CHECK_INTERVAL = 12; // hours
    const GRACE_PERIOD = 48; // hours

    /**
     * Get the current license status and associated data.
     */
    public function getStatus()
    {
        return Cache::remember(self::CACHE_KEY, now()->addMinutes(30), function () {
            $status = Setting::getValue('license_status', 'pending');
            $lastCheck = Setting::getValue('license_last_check');
            $daysLeft = Setting::getValue('license_days_left', 30);
            
            $isGrace = false;
            $graceDaysLeft = 0;

            if ($lastCheck) {
                $lastCheckDate = Carbon::parse($lastCheck);
                $diffHours = $lastCheckDate->diffInHours(now());
                
                // If it's more than 12h but strictly less than 48h, it's considered grace if unreachable
                if ($diffHours > self::CHECK_INTERVAL && $diffHours <= self::GRACE_PERIOD) {
                    $isGrace = true;
                    $graceDaysLeft = round((self::GRACE_PERIOD - $diffHours) / 24, 1);
                }
            }

            return (object) [
                'status' => $status,
                'last_check' => $lastCheck,
                'days_left' => (int) $daysLeft,
                'is_grace' => $isGrace,
                'grace_days_left' => $graceDaysLeft,
                'key' => Setting::getValue('license_key'),
            ];
        });
    }

    /**
     * Synchronize license status with Master App.
     */
    public function sync()
    {
        $key = Setting::getValue('license_key');
        if (!$key) {
            return ['error' => 'No license key configured.'];
        }

        try {
            $response = Http::timeout(10)->post(self::API_URL, [
                'license_key' => $key,
                'fingerprint' => request()->getHost(),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Expected response: { status: 'active'|'denied', days_left: int }
                Setting::setValue('license_status', $data['status'] ?? 'denied');
                Setting::setValue('license_days_left', $data['days_left'] ?? 0);
                Setting::setValue('license_last_check', now()->toDateTimeString());
                
                Cache::forget(self::CACHE_KEY);
                return ['success' => true, 'data' => $data];
            }

            return ['error' => 'Master App returned an error: ' . $response->status()];
        } catch (\Exception $e) {
            return ['error' => 'Could not connect to Master App: ' . $e->getMessage()];
        }
    }

    /**
     * Check if the application should be accessible.
     */
    public function isAccessible()
    {
        $data = $this->getStatus();
        
        if ($data->status === 'denied') {
            return false;
        }

        // If it's pending and we have no last check, we should probably try to sync once
        if ($data->status === 'pending' && !$data->last_check) {
            $result = $this->sync();
            if (isset($result['success']) && $result['data']['status'] === 'active') {
                return true;
            }
            return false;
        }

        // If we are past the grace period (48h offline)
        if ($data->last_check && Carbon::parse($data->last_check)->diffInHours(now()) > self::GRACE_PERIOD) {
            return false;
        }

        return true;
    }
}
