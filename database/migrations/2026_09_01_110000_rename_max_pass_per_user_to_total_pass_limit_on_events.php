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
            if (Schema::hasColumn('events', 'max_pass_per_user')) {
                $table->dropColumn('max_pass_per_user');
            }
            if (!Schema::hasColumn('events', 'total_pass_limit')) {
                $table->unsignedInteger('total_pass_limit')->nullable()->after('pass_fee');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('total_pass_limit');
            $table->unsignedInteger('max_pass_per_user')->nullable()->after('pass_fee');
        });
    }
};
