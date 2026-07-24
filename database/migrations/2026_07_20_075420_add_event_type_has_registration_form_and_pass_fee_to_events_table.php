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
            $table->string('event_type')->default('normal')->after('title'); // normal, inam_vitaran, yuva_melo
            $table->boolean('has_registration_form')->default(true)->after('registration_option');
            $table->decimal('pass_fee', 10, 2)->default(0.00)->after('has_registration_form');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['event_type', 'has_registration_form', 'pass_fee']);
        });
    }
};
