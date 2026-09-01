<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Setting::updateOrCreate(
            ['key' => 'mail_from_name'],
            ['value' => 'Shree Satwara Gnati Mandal, Ahmedabad']
        );

        Setting::updateOrCreate(
            ['key' => 'website_name'],
            ['value' => 'Shree Satwara Gnati Mandal, Ahmedabad']
        );

        Setting::updateOrCreate(
            ['key' => 'seo_title'],
            ['value' => 'Shree Satwara Gnati Mandal, Ahmedabad']
        );

        Setting::updateOrCreate(
            ['key' => 'seo_description'],
            ['value' => 'Welcome to the official portal of the Shree Satwara Gnati Mandal, Ahmedabad. Stay connected, register your business, view events, and manage membership details.']
        );

        Setting::updateOrCreate(
            ['key' => 'contact_address'],
            ['value' => '1, Satwara Samaj Bhavan, Opp. Siddheswar Shopping, Viratnagar-Manmohan Road, Odhav, Ahmedabad -382415']
        );

        Setting::updateOrCreate(
            ['key' => 'contact_phone'],
            ['value' => '+91-6353785519']
        );

        Setting::updateOrCreate(
            ['key' => 'footer_text'],
            ['value' => '© ' . date('Y') . ' Shree Satwara Gnati Mandal, Ahmedabad. All rights reserved.']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
