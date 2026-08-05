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
        Schema::table('management_desk', function (Blueprint $table) {
            if (!Schema::hasColumn('management_desk', 'name_gu')) {
                $table->string('name_gu')->nullable()->after('name');
            }
            if (!Schema::hasColumn('management_desk', 'designation_gu')) {
                $table->string('designation_gu')->nullable()->after('designation');
            }
            if (Schema::hasColumn('management_desk', 'message')) {
                $table->dropColumn('message');
            }
            if (Schema::hasColumn('management_desk', 'message_gu')) {
                $table->dropColumn('message_gu');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('management_desk', function (Blueprint $table) {
            $table->dropColumn(['name_gu', 'designation_gu']);
            $table->text('message')->nullable();
        });
    }
};
