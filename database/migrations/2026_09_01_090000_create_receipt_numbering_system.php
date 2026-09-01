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
        Schema::create('receipt_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('financial_year')->unique();
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'receipt_no')) {
                $table->string('receipt_no')->nullable()->unique()->after('payment_amount');
            }
        });

        Schema::table('businesses', function (Blueprint $table) {
            if (!Schema::hasColumn('businesses', 'receipt_no')) {
                $table->string('receipt_no')->nullable()->unique()->after('payment_amount');
            }
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('event_registrations', 'receipt_no')) {
                $table->string('receipt_no')->nullable()->unique()->after('payment_amount');
            }
        });

        Schema::table('event_sponsors', function (Blueprint $table) {
            if (!Schema::hasColumn('event_sponsors', 'receipt_no')) {
                $table->string('receipt_no')->nullable()->unique()->after('payment_id');
            }
        });

        Schema::table('business_payment_links', function (Blueprint $table) {
            if (!Schema::hasColumn('business_payment_links', 'receipt_no')) {
                $table->string('receipt_no')->nullable()->unique()->after('razorpay_payment_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('receipt_no');
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('receipt_no');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn('receipt_no');
        });

        Schema::table('event_sponsors', function (Blueprint $table) {
            $table->dropColumn('receipt_no');
        });

        Schema::table('business_payment_links', function (Blueprint $table) {
            $table->dropColumn('receipt_no');
        });

        Schema::dropIfExists('receipt_sequences');
    }
};
