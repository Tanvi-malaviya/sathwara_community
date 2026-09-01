<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'form_start_date')) {
                $table->date('form_start_date')->nullable()->after('has_registration_form');
            }
            if (!Schema::hasColumn('events', 'form_end_date')) {
                $table->date('form_end_date')->nullable()->after('form_start_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['form_start_date', 'form_end_date']);
        });
    }
};
