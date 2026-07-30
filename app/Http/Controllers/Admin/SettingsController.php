<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Show Settings Form
     */
    public function index()
    {
        $settings = [
            'website_name' => Setting::get('website_name', 'Sathwara Community Portal'),
            'website_logo' => Setting::get('website_logo'),
            'website_favicon' => Setting::get('website_favicon'),
            'primary_color' => Setting::get('primary_color', '#2563EB'),
            'seo_title' => Setting::get('seo_title'),
            'seo_description' => Setting::get('seo_description'),
            'contact_address' => Setting::get('contact_address'),
            'contact_email' => Setting::get('contact_email'),
            'contact_phone' => Setting::get('contact_phone'),
            'contact_whatsapp' => Setting::get('contact_whatsapp'),
            'contact_map_iframe' => Setting::get('contact_map_iframe'),
            'social_facebook' => Setting::get('social_facebook'),
            'social_twitter' => Setting::get('social_twitter'),
            'social_instagram' => Setting::get('social_instagram'),
            'social_youtube' => Setting::get('social_youtube'),
            'about_mission' => Setting::get('about_mission'),
            'about_vision' => Setting::get('about_vision'),
            'about_history' => Setting::get('about_history'),
            'about_objectives' => Setting::get('about_objectives'),
            'footer_text' => Setting::get('footer_text', '© ' . date('Y') . ' Sathwara Community. All rights reserved.'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Save/Update Settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'website_name' => 'required|string|max:255',
            'primary_color' => 'nullable|string|max:10',
            'contact_email' => 'nullable|email|max:255',
            'website_logo' => 'nullable|image|max:1024',
            'website_favicon' => 'nullable|image|max:512',
        ]);

        $keys = $request->except(['_token', 'website_logo', 'website_favicon']);

        foreach ($keys as $key => $val) {
            Setting::set($key, $val);
        }

        // Logo Upload
        if ($request->hasFile('website_logo')) {
            $oldLogo = Setting::get('website_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('website_logo')->store('settings', 'public');
            Setting::set('website_logo', $logoPath);
        }

        // Favicon Upload
        if ($request->hasFile('website_favicon')) {
            $oldFavicon = Setting::get('website_favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $faviconPath = $request->file('website_favicon')->store('settings', 'public');
            Setting::set('website_favicon', $faviconPath);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
