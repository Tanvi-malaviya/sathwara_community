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
        Schema::table('member_profiles', function (Blueprint $table) {
            $table->string('gender')->nullable()->change();
            $table->date('dob')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('state')->nullable()->change();
            $table->string('pincode')->nullable()->change();
            $table->string('photo_path')->nullable()->change();
            $table->string('aadhaar_number')->nullable()->change();
            $table->string('aadhaar_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_profiles', function (Blueprint $table) {
            $table->string('gender')->nullable(false)->change();
            $table->date('dob')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('state')->nullable(false)->change();
            $table->string('pincode')->nullable(false)->change();
            $table->string('photo_path')->nullable(false)->change();
            $table->string('aadhaar_number')->nullable(false)->change();
            $table->string('aadhaar_path')->nullable(false)->change();
        });
    }
};
