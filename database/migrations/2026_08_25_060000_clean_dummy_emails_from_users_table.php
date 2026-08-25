<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')
            ->where(function ($q) {
                $q->where('email', 'like', '%@sathwaracommunity.org')
                  ->orWhere('email', 'like', '%@example.com')
                  ->orWhere('email', 'like', 'dummy%')
                  ->orWhere('email', 'like', '%@noemail.com');
            })
            ->update(['email' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Dummy emails cannot and should not be restored
    }
};
