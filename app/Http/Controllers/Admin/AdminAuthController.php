<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\MemberProfile;

class AdminAuthController extends Controller
{
    /**
     * Show dedicated Admin Login Form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole('Administrator')) {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('admin.auth.login');
    }

    /**
     * Handle Admin Login Submission
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim($request->login);

        // Find user by Email, User ID, or Phone
        $user = null;
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginInput)->first();
        } else {
            $numericId = (int) preg_replace('/[^0-9]/', '', $loginInput);
            if ($numericId > 0) {
                $user = User::find($numericId);
            }
            if (!$user) {
                $profile = MemberProfile::where('phone', $loginInput)->first();
                if ($profile) {
                    $user = $profile->user;
                }
            }
        }

        if (!$user) {
            return back()->withInput()->withErrors([
                'login' => 'No admin account found matching the provided input.',
            ]);
        }

        // Attempt password login using retrieved user's email
        if (!Auth::attempt(['email' => $user->email, 'password' => $request->password], $request->boolean('remember'))) {
            return back()->withInput()->withErrors([
                'password' => 'The password provided is incorrect.',
            ]);
        }

        $authenticatedUser = Auth::user();

        // Enforce Administrator or Sub Admin Role Only
        if (!$authenticatedUser->hasAnyRole(['Administrator', 'Sub Admin'])) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withInput()->withErrors([
                'login' => 'Access Denied: Only Administrators and Sub-Admins can sign in to the Admin Portal.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')->with('success', 'Welcome to Admin Portal Dashboard.');
    }

    /**
     * Admin Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been logged out of the Admin Portal.');
    }
}
