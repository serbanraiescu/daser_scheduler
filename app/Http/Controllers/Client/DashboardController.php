<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Client;

class DashboardController extends Controller
{
    public function index()
    {
        $client = auth()->user()->client;

        if (!$client) {
            return view('client.no-history');
        }

        $upcomingBooking = $client->bookings()
            ->where('date', '>=', now()->toDateString())
            ->where('status', '!=', 'cancelled')
            ->orderBy('date')
            ->orderBy('start_time')
            ->first();

        $recentHistory = $client->bookings()
            ->where('date', '<', now()->toDateString())
            ->latest('date')
            ->take(5)
            ->get();

        $voucherCount = $client->vouchers()->where('used', false)->count();

        return view('client.dashboard', compact('client', 'upcomingBooking', 'recentHistory', 'voucherCount'));
    }

    public function history()
    {
        Client::syncOrphanBookings(auth()->user());
        $client = auth()->user()->client;

        if (!$client) {
            return view('client.no-history');
        }

        $upcomingBooking = $client->bookings()
            ->where('date', '>=', now()->toDateString())
            ->where('status', '!=', 'cancelled')
            ->orderBy('date')
            ->orderBy('start_time')
            ->first();

        $bookings = $client->bookings()
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15);
            
        $voucherCount = $client->vouchers()->where('used', false)->count();

        return view('client.history', compact('bookings', 'client', 'upcomingBooking', 'voucherCount'));
    }

    public function vouchers()
    {
        Client::syncOrphanBookings(auth()->user());
        $client = auth()->user()->client;

        if (!$client) {
            return view('client.no-history');
        }

        $upcomingBooking = $client->bookings()
            ->where('date', '>=', now()->toDateString())
            ->where('status', '!=', 'cancelled')
            ->orderBy('date')
            ->orderBy('start_time')
            ->first();

        $vouchers = $client->vouchers()->with('voucher')->paginate(20);
        $voucherCount = $client->vouchers()->where('used', false)->count();

        return view('client.vouchers', compact('vouchers', 'client', 'upcomingBooking', 'voucherCount'));
    }
}
