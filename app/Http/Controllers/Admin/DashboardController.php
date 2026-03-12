<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Service;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        
        $bookingsToday = Booking::where('date', $today)->count();
        
        $revenueToday = Booking::where('date', $today)
            ->where('status', '!=', 'cancelled')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price');
        
        $upcomingBookings = Booking::where('date', '>=', $today)
            ->where('status', 'confirmed')
            ->with(['client', 'employee', 'service'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        $employees = User::where('role', 'employee')->get();
        $services = Service::where('active', true)->get();

        return view('admin.dashboard', compact(
            'bookingsToday', 
            'revenueToday', 
            'upcomingBookings',
            'employees',
            'services'
        ));
    }
}
