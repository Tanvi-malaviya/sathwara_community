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
                    if ($user->email === 'admin@community.com') {
                        $user->member_code = 'ADMIN001';
                    } elseif ($user->email === 'member@community.com') {
                        $user->member_code = 'MEMBER001';
                    } elseif ($user->email === 'pending@community.com') {
                        $user->member_code = 'PENDING001';
                    } else {
                        $user->member_code = 'SSAM' . sprintf('%04d', $user->id);
                    }
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
