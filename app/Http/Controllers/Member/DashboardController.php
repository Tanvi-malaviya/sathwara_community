<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MemberProfile;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Member Dashboard Index
     */
    public function index()
    {
        $user = auth()->user();
        $profile = $user->memberProfile;
        $family = $user->familyMembers;
        $familyCount = $family->count();
        $registeredEvents = $user->registeredEvents()->where('date', '>=', now()->toDateString())->get();
        
        return view('member.dashboard', compact('user', 'profile', 'family', 'familyCount', 'registeredEvents'));
    }

    /**
     * Account Status Display (Pending/Rejected Feedback)
     */
    public function status()
    {
        $user = auth()->user();
        
        if ($user->status === 'approved' || $user->hasRole('Administrator')) {
            return redirect()->route('member.dashboard');
        }

        return view('member.status', compact('user'));
    }

    /**
     * Edit Profile Form
     */
    public function editProfile()
    {
        $user = auth()->user();
        $profile = $user->memberProfile;
        return view('member.profile', compact('user', 'profile'));
    }

    /**
     * Update Profile Info
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $profile = $user->memberProfile;

        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'father_member_id' => 'nullable|string|max:50',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date|before:today',
            'blood_group' => 'nullable|string|max:10',
            'education' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'phone' => 'required|digits:10',
            'whatsapp' => 'nullable|digits:10',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Update User name
        $user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
        ]);

        // Upload new photo if provided
        $photoPath = $profile->photo_path;
        if ($request->hasFile('photo')) {
            // Delete old photo
            if (Storage::disk('public')->exists($profile->photo_path) && !str_starts_with($profile->photo_path, 'http')) {
                Storage::disk('public')->delete($profile->photo_path);
            }
            $photoPath = $request->file('photo')->store('registrations/photos', 'public');
        }

        // Update Profile
        $profile->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'father_member_id' => $request->father_member_id,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'blood_group' => $request->blood_group,
            'education' => $request->education,
            'occupation' => $request->occupation,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'photo_path' => $photoPath,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password does not match our records.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    /**
     * View/Print Membership Card
     */
    public function membershipCard()
    {
        $user = auth()->user();
        $profile = $user->memberProfile;
        
        if (!$profile) {
            return redirect()->route('member.dashboard')->with('error', 'Profile not found.');
        }

        return view('member.card', compact('user', 'profile'));
    }
}
