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
            ['value' => 'Satwara Community Portal']
        );

        Setting::updateOrCreate(
            ['key' => 'website_name'],
            ['value' => 'Satwara Community Portal']
        );

        Setting::updateOrCreate(
            ['key' => 'seo_title'],
            ['value' => 'Satwara Community Management System']
        );

        Setting::updateOrCreate(
            ['key' => 'seo_description'],
            ['value' => 'Welcome to the official portal of the Satwara Community. Stay connected, register your business, view events, and manage membership details.']
        );

        Setting::updateOrCreate(
            ['key' => 'contact_address'],
            ['value' => 'Satwara Community Bhawan, near RTO, Ashram Road, Ahmedabad, Gujarat, 380009']
        );

        Setting::updateOrCreate(
            ['key' => 'footer_text'],
            ['value' => '© ' . date('Y') . ' Satwara Community. All rights reserved.']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
