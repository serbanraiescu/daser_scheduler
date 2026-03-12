<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();
        $bookingsToday = \App\Models\Booking::whereDate('start_time', $today)->count();
        $revenueToday = \App\Models\Booking::whereDate('start_time', $today)
            ->where('status', '!=', 'cancelled')
            ->sum('price_at_booking');
        
        $upcomingBookings = \App\Models\Booking::where('start_time', '>', now())
            ->where('status', 'confirmed')
            ->with(['client', 'employee', 'service'])
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        $employees = \App\Models\User::where('role', 'employee')->get();
        $services = \App\Models\Service::where('is_active', true)->get();

        return view('admin.dashboard', compact(
            'bookingsToday', 
            'revenueToday', 
            'upcomingBookings',
            'employees',
            'services'
        ));
    }
}
