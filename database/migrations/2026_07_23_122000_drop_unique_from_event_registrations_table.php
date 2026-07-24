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
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->index('event_id');
            $table->index('user_id');
            $table->dropUnique('event_registrations_event_id_user_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->unique(['event_id', 'user_id']);
            $table->dropIndex(['event_id']);
            $table->dropIndex(['user_id']);
        });
    }
};
