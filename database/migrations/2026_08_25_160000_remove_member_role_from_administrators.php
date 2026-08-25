<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\MemberProfile;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Remove 'Member' role from all Administrators and Sub-Admins
        $adminUsers = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Administrator', 'Sub-Admin']);
        })->get();

        foreach ($adminUsers as $admin) {
            if ($admin->hasRole('Member')) {
                $admin->removeRole('Member');
            }
            // If admin has a member profile, clean it up so it never appears in member queries
            MemberProfile::where('user_id', $admin->id)->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed
    }
};
