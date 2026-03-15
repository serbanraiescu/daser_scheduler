<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GiftVoucher;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Support\Str;

class GiftVoucherController extends Controller
{
    public function index()
    {
        $giftVouchers = GiftVoucher::with(['client', 'service'])->latest()->paginate(15);
        
        $stats = [
            'total_sold' => GiftVoucher::count(),
            'active' => GiftVoucher::where('status', 'active')->count(),
            'redeemed' => GiftVoucher::where('status', 'redeemed')->count(),
        ];

        return view('admin.gift_vouchers.index', compact('giftVouchers', 'stats'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $services = Service::where('active', true)->orderBy('name')->get();
        return view('admin.gift_vouchers.create', compact('clients', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'buyer_name' => 'nullable|string|max:255',
            'buyer_email' => 'nullable|email|max:255',
            'buyer_phone' => 'nullable|string|max:20',
            'type' => 'required|in:value,package',
            'value_amount' => 'nullable|required_if:type,value|numeric|min:0',
            'service_id' => 'nullable|required_if:type,package|exists:services,id',
            'remaining_uses' => 'nullable|required_if:type,package|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $validated['code'] = 'GFT-' . strtoupper(Str::random(8));
        $validated['status'] = 'active';

        if ($request->type === 'value') {
            $validated['remaining_value'] = $request->value_amount;
            $validated['service_id'] = null;
            $validated['remaining_uses'] = null;
        } else {
            $validated['value_amount'] = null;
            $validated['remaining_value'] = null;
        }

        $giftVoucher = GiftVoucher::create($validated);

        // Notify buyer if email is present
        if ($giftVoucher->buyer_email) {
            // we will create this notification later
            // $giftVoucher->notify(new \App\Notifications\GiftVoucherIssued($giftVoucher));
        }

        return redirect()->route('admin.gift-vouchers.index')->with('success', 'Cardul cadou a fost creat cu succes.');
    }

    public function show(GiftVoucher $giftVoucher)
    {
        return view('admin.gift_vouchers.show', compact('giftVoucher'));
    }

    public function update(Request $request, GiftVoucher $giftVoucher)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,redeemed,expired',
            'expires_at' => 'nullable|date',
            'remaining_value' => 'nullable|numeric|min:0',
            'remaining_uses' => 'nullable|integer|min:0',
        ]);

        $giftVoucher->update($validated);

        return back()->with('success', 'Cardul cadou a fost actualizat.');
    }

    public function destroy(GiftVoucher $giftVoucher)
    {
        $giftVoucher->delete();
        return redirect()->route('admin.gift-vouchers.index')->with('success', 'Cardul cadou a fost șters.');
    }
}
