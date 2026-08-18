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
        // 1. Add payment fields to users table (for Member Signup)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'payment_status')) {
                $table->string('payment_status')->nullable()->default('unpaid')->after('payment_id');
            }
            if (!Schema::hasColumn('users', 'payment_amount')) {
                $table->decimal('payment_amount', 10, 2)->nullable()->default(0.00)->after('payment_status');
            }
        });

        // 2. Add payment fields to businesses table (for Business Register)
        Schema::table('businesses', function (Blueprint $table) {
            if (!Schema::hasColumn('businesses', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('status');
            }
            if (!Schema::hasColumn('businesses', 'payment_status')) {
                $table->string('payment_status')->nullable()->default('unpaid')->after('payment_id');
            }
            if (!Schema::hasColumn('businesses', 'payment_amount')) {
                $table->decimal('payment_amount', 10, 2)->nullable()->default(0.00)->after('payment_status');
            }
        });

        // 3. Add payment fields to event_registrations table (for Event Booking)
        Schema::table('event_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('event_registrations', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('status');
            }
            if (!Schema::hasColumn('event_registrations', 'payment_status')) {
                $table->string('payment_status')->nullable()->default('unpaid')->after('payment_id');
            }
            if (!Schema::hasColumn('event_registrations', 'payment_amount')) {
                $table->decimal('payment_amount', 10, 2)->nullable()->default(0.00)->after('payment_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['payment_id', 'payment_status', 'payment_amount']);
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['payment_id', 'payment_status', 'payment_amount']);
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn(['payment_id', 'payment_status', 'payment_amount']);
        });
    }
};
