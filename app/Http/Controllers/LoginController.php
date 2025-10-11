<?php

namespace App\Http\Controllers;

use App\Mail\UserRegistered;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Validation failed');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->back()->with('success', 'Login successful');
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'agree_field' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Validation failed');
        }

        // Get default user role (assuming role_id 2 is for regular users)
        $userRole = Role::where('name', 'user')->first();
        if (!$userRole) {
            $userRole = Role::first(); // Fallback to first role
        }

        $user = User::create([
            'firstname' => $request->first_name,
            'lastname' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $userRole->id,
            'status' => 1,
        ]);

        // Send welcome/verification email
        try {
            Mail::to($user->email)->send(new UserRegistered($user));
            Log::info('Registration email sent successfully', ['user_id' => $user->id, 'email' => $user->email]);
        } catch (\Exception $e) {
            Log::error('Failed to send registration email', ['error' => $e->getMessage(), 'user_id' => $user->id]);
        }

        // Auto-login after registration
        Auth::login($user);

        return redirect()->back()->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Check if request is AJAX (for website modals)
        if ($request->ajax()) {
            return redirect()->route('index')->with('success', 'You have been logged out successfully.');
        }

        // For regular form submissions (admin logout)
        return redirect()->route('index')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Handle forgot password
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Validation failed');
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->back()->with('success', 'Password reset link sent to your email');
        }

        return redirect()->back()->with('error', 'Unable to send reset link');
    }

    /**
     * Handle email verification
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Verify the hash matches
        if (!hash_equals((string) $hash, sha1($user->email))) {
            return redirect()->route('index')->with('error', 'Invalid verification link.');
        }

        // Check if already verified
        if ($user->email_verified_at) {
            return redirect()->route('index')->with('info', 'Email already verified.');
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->save();

        Log::info('Email verified successfully', ['user_id' => $user->id]);

        return redirect()->route('index')->with('success', 'Email verified successfully! You can now enjoy all features.');
    }

    /**
     * Handle password change
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Validation failed');
        }

        // $user = Auth::user();
        // find user
        $user = User::find(Auth::user()->id);
        
        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect');
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully');
    }
}
