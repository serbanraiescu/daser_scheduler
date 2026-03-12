<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use App\Services\LicenseService;

class LicenseKillController extends Controller
{
    public function kill(Request $request)
    {
        $killToken = Setting::getValue('license_kill_token');
        $requestToken = $request->header('X-Kill-Token') ?: $request->input('kill_token');

        if (!$killToken || $killToken !== $requestToken) {
            return response()->json(['error' => 'Unauthorized kill request.'], 401);
        }

        Setting::setValue('license_status', 'denied');
        Setting::setValue('license_days_left', 0);
        Setting::setValue('license_last_check', now()->toDateTimeString());
        
        Cache::forget(LicenseService::CACHE_KEY);

        return response()->json(['success' => true, 'message' => 'License revoked successfully.']);
    }
}
