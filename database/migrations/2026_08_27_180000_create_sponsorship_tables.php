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
        // 1. Sponsorship Types (Packages per Event)
        Schema::create('sponsorship_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('title');
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->unsignedInteger('max_sponsors')->nullable()->default(0)->comment('0 or null means unlimited');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // 2. Registered Sponsors
        Schema::create('event_sponsors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('sponsorship_type_id')->nullable()->constrained('sponsorship_types')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('mobile', 30);
            $table->string('email')->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('logo_path')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->string('payment_status', 30)->default('pending'); // pending, received, failed
            $table->string('payment_id')->nullable();
            $table->string('status', 30)->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_sponsors');
        Schema::dropIfExists('sponsorship_types');
    }
};
