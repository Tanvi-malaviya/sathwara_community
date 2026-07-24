<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('membership_status')->default('active')->after('status');
        });

        // Migrate existing status 'inactive' records
        DB::table('businesses')
            ->where('status', 'inactive')
            ->update([
                'status' => 'approved',
                'membership_status' => 'inactive'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('membership_status');
        });
    }
};
