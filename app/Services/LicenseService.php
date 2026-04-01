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
            $key = Setting::getValue('license_key');
            
            \Log::info("License Check (getStatus): Status={$status}, LastCheck={$lastCheck}, Key=" . ($key ? 'Configured' : 'Missing'));

            $isGrace = false;
            $graceDaysLeft = 0;

            if ($lastCheck) {
                $lastCheckDate = \Carbon\Carbon::parse($lastCheck);
                $diffHours = $lastCheckDate->diffInHours(now());
                
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
                'key' => $key,
            ];
        });
    }

    /**
     * Synchronize license status with Master App.
     */
    public function sync()
    {
        $key = Setting::getValue('license_key');
        \Log::info("License Sync Started: Key=" . ($key ? 'present' : 'missing'));

        if (!$key) {
            \Log::warning("License Sync Aborted: No key configured.");
            return ['error' => 'No license key configured.'];
        }

        try {
            $response = Http::timeout(10)->post(self::API_URL, [
                'license_key' => $key,
                'fingerprint' => request()->getHost(),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                \Log::info("License Sync Successful: ResponseData=" . json_encode($data));
                
                Setting::setValue('license_status', $data['status'] ?? 'denied');
                Setting::setValue('license_days_left', $data['days_left'] ?? 0);
                Setting::setValue('license_last_check', now()->toDateTimeString());
                
                Cache::forget(self::CACHE_KEY);
                return ['success' => true, 'data' => $data];
            }

            \Log::error("License Sync Failed: Master App returned {$response->status()}");
            return ['error' => 'Master App returned an error: ' . $response->status()];
        } catch (\Exception $e) {
            \Log::error("License Sync Failed: Exception=" . $e->getMessage());
            return ['error' => 'Could not connect to Master App: ' . $e->getMessage()];
        }
    }

    /**
     * Check if the application should be accessible.
     */
    public function isAccessible()
    {
        $data = $this->getStatus();
        
        // If status is hard-denied, block immediately
        if ($data->status === 'denied') {
            \Log::warning("License blocking: Status is DENIED.");
            return false;
        }

        // Calculate hours since last sync
        $hoursSinceLastSync = 999; 
        if ($data->last_check) {
            $hoursSinceLastSync = \Carbon\Carbon::parse($data->last_check)->diffInHours(now());
        }

        \Log::info("License Accessibility Check: Status={$data->status}, HoursSinceSync={$hoursSinceLastSync}");

        // PROACTIVE SYNC: If it's been more than 12h, try a background sync
        if ($hoursSinceLastSync > self::CHECK_INTERVAL || ($data->status === 'pending' && !$data->last_check)) {
            \Log::info("License: Triggering proactive sync (Interval reached or pending).");
            $result = $this->sync();
            
            // If we just synced, re-fetch data internally
            if (isset($result['success'])) {
                $data->status = $result['data']['status'] ?? $data->status;
                $hoursSinceLastSync = 0;
            }
        }

        // BLOCKING LOGIC: Only block if status is denied OR we are past the 48h grace period
        if ($hoursSinceLastSync > self::GRACE_PERIOD) {
            \Log::error("License blocking: Sync grace period exceeded (48h). Last sync was {$hoursSinceLastSync} hours ago.");
            return false;
        }

        if ($data->status === 'denied') {
             return false;
        }

        return true;
    }
}
