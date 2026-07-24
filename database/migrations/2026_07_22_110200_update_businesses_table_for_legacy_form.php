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
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('member_id')->nullable()->after('user_id');
            $table->foreignId('area_id')->nullable()->after('category_id')->constrained('areas')->onDelete('set null');
            // $table->string('payment_screenshot_path')->nullable()->after('logo_path');
            
            // Make category_id and description nullable to support optional state
            $table->foreignId('category_id')->nullable()->change();
            $table->text('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            // $table->dropColumn(['member_id', 'area_id', 'payment_screenshot_path']);
            
            // Revert nullable columns
            $table->foreignId('category_id')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
        });
    }
};
