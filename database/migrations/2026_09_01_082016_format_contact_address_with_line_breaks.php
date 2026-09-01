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
            ['key' => 'contact_address'],
            ['value' => "1, Satwara Samaj Bhavan,\nOpp. Siddheswar Shopping,\nViratnagar-Manmohan Road,\nOdhav, Ahmedabad -382415"]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
