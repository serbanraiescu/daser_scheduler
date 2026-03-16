<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class WebsiteController extends Controller
{
    public function index()
    {
        $settings = WebsiteSetting::first() ?? new WebsiteSetting();
        $pages = Page::orderBy('created_at', 'desc')->get();
        return view('admin.website.index', compact('settings', 'pages'));
    }

    public function updateSettings(Request $request)
    {
        $settings = WebsiteSetting::first() ?? new WebsiteSetting();
        
        $validated = $request->validate([
            'business_name' => 'nullable|string|max:255',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:500',
            'hero_button_text' => 'nullable|string|max:255',
            
            'services_title' => 'nullable|string|max:255',
            'services_subtitle' => 'nullable|string|max:255',
            
            'team_title' => 'nullable|string|max:255',
            'team_subtitle' => 'nullable|string|max:1000',
            'team_reservation_text' => 'nullable|string|max:255',
            
            'gallery_title' => 'nullable|string|max:255',
            'gallery_subtitle' => 'nullable|string|max:255',
            'gallery_zoom_text' => 'nullable|string|max:255',
            
            'reviews_title' => 'nullable|string|max:255',
            
            'contact_title' => 'nullable|string|max:255',
            'contact_label_location' => 'nullable|string|max:255',
            'contact_label_phone' => 'nullable|string|max:255',
            'contact_button_call_text' => 'nullable|string|max:255',
            'contact_button_book_text' => 'nullable|string|max:255',
            
            'about_title' => 'nullable|string|max:255',
            'about_text' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'map_embed_url' => 'nullable|string',
            'instagram_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'primary_color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'secondary_color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            
            'primary_font' => 'nullable|string|max:255',
            'secondary_font' => 'nullable|string|max:255',
            'border_radius' => 'nullable|string|max:255',
            'section_padding' => 'nullable|string|max:255',
            'custom_css' => 'nullable|string',
            
            'show_services_section' => 'boolean',
            'show_about_section' => 'boolean',
            'show_contact_section' => 'boolean',
            'show_team_section' => 'boolean',
            'show_gallery_section' => 'boolean',
            'show_reviews_section' => 'boolean',
            
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_alt' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hero' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        // Handle Booleans
        $settings->show_services_section = $request->has('show_services_section');
        $settings->show_about_section = $request->has('show_about_section');
        $settings->show_contact_section = $request->has('show_contact_section');
        $settings->show_team_section = $request->has('show_team_section');
        $settings->show_gallery_section = $request->has('show_gallery_section');
        $settings->show_reviews_section = $request->has('show_reviews_section');

        // Handle Images
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = public_path('uploads/website');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $file->move($path, $filename);
            $settings->logo_url = asset('uploads/website/' . $filename);
        }

        if ($request->hasFile('logo_alt')) {
            $file = $request->file('logo_alt');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = public_path('uploads/website');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $file->move($path, $filename);
            $settings->logo_alt_url = asset('uploads/website/' . $filename);
        }

        if ($request->hasFile('hero')) {
            $file = $request->file('hero');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/website'), $filename);
            $settings->hero_image = asset('uploads/website/' . $filename);
        }

        $settings->fill($request->except([
            'logo', 'logo_alt', 'hero', 
            'show_services_section', 'show_about_section', 'show_contact_section',
            'show_team_section', 'show_gallery_section', 'show_reviews_section'
        ]));
        $settings->save();

        return back()->with('success', 'Website settings updated successfully.');
    }

    public function pageCreate()
    {
        return view('admin.website.pages.create');
    }

    public function pageStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'show_in_footer' => 'boolean',
            'show_in_header' => 'boolean',
            'status' => 'required|in:published,draft',
        ]);

        $validated['slug'] = Str::slug($request->title);
        
        $count = Page::where('slug', 'like', $validated['slug'] . '%')->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        Page::create($validated);

        return redirect()->route('admin.website.index')->with('success', 'Page created successfully.');
    }

    public function pageEdit(Page $page)
    {
        return view('admin.website.pages.edit', compact('page'));
    }

    public function pageUpdate(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'show_in_footer' => 'boolean',
            'show_in_header' => 'boolean',
            'status' => 'required|in:published,draft',
        ]);

        $page->show_in_footer = $request->has('show_in_footer');
        $page->show_in_header = $request->has('show_in_header');
        
        $page->update($validated);

        return redirect()->route('admin.website.index')->with('success', 'Page updated successfully.');
    }

    public function pageDestroy(Page $page)
    {
        $page->delete();
        return back()->with('success', 'Page deleted successfully.');
    }
}
