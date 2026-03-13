<?php

namespace App\Http\Controllers;

use App\Models\WebsiteSetting;
use App\Models\Page;
use App\Models\Service;
use App\Models\Employee;
use Illuminate\Http\Request;

class PublicWebsiteController extends Controller
{
    public function index()
    {
        $settings = WebsiteSetting::first() ?? new WebsiteSetting();
        $services = Service::where('active', true)->get();
        $employees = Employee::with('user')->where('active', true)->get();
        $pagesHeader = Page::where('status', 'published')->where('show_in_header', true)->get();
        $pagesFooter = Page::where('status', 'published')->where('show_in_footer', true)->get();
        
        return view('public.landing', compact('settings', 'services', 'employees', 'pagesHeader', 'pagesFooter'));
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $settings = WebsiteSetting::first() ?? new WebsiteSetting();
        $pagesHeader = Page::where('status', 'published')->where('show_in_header', true)->get();
        $pagesFooter = Page::where('status', 'published')->where('show_in_footer', true)->get();

        return view('public.page', compact('page', 'settings', 'pagesHeader', 'pagesFooter'));
    }
}
