<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SmsQueue;
use Carbon\Carbon;

class SmsGatewayController extends Controller
{
    /**
     * Get pending SMS messages for the Android gateway.
     */
    public function pending(Request $request)
    {
        if ($request->get('key') !== config('sms.api_key')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $messages = SmsQueue::where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->limit(20)
            ->get(['id', 'phone', 'message']);

        return response()->json($messages);
    }

    /**
     * Confirm message delivery from the Android gateway.
     */
    public function confirm(Request $request)
    {
        if ($request->get('key') !== config('sms.api_key')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'id' => 'required|exists:sms_queue,id',
            'status' => 'required|in:sent,failed',
        ]);

        $sms = SmsQueue::findOrFail($validated['id']);
        $sms->update([
            'status' => $validated['status'],
            'sent_at' => $validated['status'] === 'sent' ? now() : null,
        ]);

        return response()->json(['success' => true]);
    }
}
