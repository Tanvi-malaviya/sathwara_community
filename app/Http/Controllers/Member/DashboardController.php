<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MemberProfile;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailOtpMail;

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
        
        $formattedMemberId = '#' . sprintf('%05d', $user->id);
        $myBusinesses = \App\Models\Business::where('user_id', $user->id)
                        ->orWhere('member_id', (string)$user->id)
                        ->orWhere('member_id', $formattedMemberId)
                        ->orWhere('member_id', '#' . $user->id)
                        ->with('category', 'area')
                        ->latest()
                        ->get();

        return view('member.dashboard', compact('user', 'profile', 'family', 'familyCount', 'registeredEvents', 'myBusinesses'));
    }

    /**
     * Display Registered Businesses for logged in member
     */
    public function myBusinesses()
    {
        $user = auth()->user();
        $formattedMemberId = '#' . sprintf('%05d', $user->id);
        $businesses = \App\Models\Business::where('user_id', $user->id)
                        ->orWhere('member_id', (string)$user->id)
                        ->orWhere('member_id', $formattedMemberId)
                        ->orWhere('member_id', '#' . $user->id)
                        ->with('category', 'area')
                        ->latest()
                        ->get();

        return view('member.my_businesses', compact('user', 'businesses'));
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
            'whatsapp' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'photo_path' => $photoPath,
        ]);

        session()->flash('success', 'Profile updated successfully.');
        session()->save();
        return redirect()->back();
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

        session()->flash('success', 'Password updated successfully.');
        session()->save();
        return redirect()->back();
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

    /**
     * Account Settings Form (Email/Password Update)
     */
    public function accountSettings()
    {
        $user = auth()->user();
        return view('member.account_settings', compact('user'));
    }

    /**
     * Send OTP for Email Update
     */
    public function sendEmailOtp(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ], [
            'email.unique' => 'This email address is already in use by another account.',
        ]);

        if ($request->email === $user->email) {
            return redirect()->back()->withErrors(['email' => 'This is already your current email address.']);
        }

        $otp = (string) mt_rand(100000, 999999);

        // Store OTP details in session
        session([
            'pending_email' => $request->email,
            'email_otp_code' => $otp,
            'email_otp_expires' => now()->addMinutes(15),
        ]);

        // Log OTP code for local debugging/testing
        \Illuminate\Support\Facades\Log::info("Email Update OTP generated for {$request->email}: {$otp}");

        // Send Email
        try {
            Mail::to($request->email)->send(new VerifyEmailOtpMail($otp, $request->email));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send Email OTP: " . $e->getMessage());
            return redirect()->back()->withErrors(['email' => 'Failed to send verification email. Please check your mail settings.']);
        }

        session()->flash('success', 'OTP sent successfully.');
        session()->flash('success_otp', 'A 6-digit verification code has been sent to ' . $request->email . '. Please enter it below to confirm.');
        session()->save();
        return redirect()->back();
    }

    /**
     * Verify OTP and Update Email Address
     */
    public function verifyEmailOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = auth()->user();
        $pendingEmail = session('pending_email');
        $otpCode = session('email_otp_code');
        $expiresAt = session('email_otp_expires');

        if (!$pendingEmail || !$otpCode || !$expiresAt) {
            return redirect()->back()->withErrors(['otp' => 'No pending email change request found. Please request a new OTP.']);
        }

        if (now()->greaterThan($expiresAt)) {
            session()->forget(['pending_email', 'email_otp_code', 'email_otp_expires']);
            return redirect()->back()->withErrors(['otp' => 'The verification code has expired. Please request a new code.']);
        }

        if ($request->otp !== $otpCode) {
            return redirect()->back()->withErrors(['otp' => 'The verification code you entered is invalid.']);
        }

        // Check uniqueness once more before updating
        if (User::where('email', $pendingEmail)->where('id', '!=', $user->id)->exists()) {
            session()->forget(['pending_email', 'email_otp_code', 'email_otp_expires']);
            return redirect()->back()->withErrors(['otp' => 'This email address is already taken. Please try another one.']);
        }

        // Perform the update
        $user->update([
            'email' => $pendingEmail,
        ]);

        // Clear session
        session()->forget(['pending_email', 'email_otp_code', 'email_otp_expires', 'success_otp']);

        session()->flash('success', 'Your login email address has been updated successfully.');
        session()->save();
        return redirect()->route('member.account.settings');
    }

    /**
     * Cancel Pending Email Change Request
     */
    public function cancelEmailOtp()
    {
        session()->forget(['pending_email', 'email_otp_code', 'email_otp_expires', 'success_otp']);
        return redirect()->route('member.account.settings');
    }
}
