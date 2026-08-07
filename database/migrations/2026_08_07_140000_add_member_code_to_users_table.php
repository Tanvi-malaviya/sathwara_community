<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'member_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('member_code')->nullable()->after('id');
            });

            // Populate member_code for existing users
            $users = User::all();
            foreach ($users as $user) {
                if (empty($user->member_code)) {
                    $user->member_code = 'SSAM' . sprintf('%04d', $user->id);
                    $user->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'member_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('member_code');
            });
        }
    }
};
