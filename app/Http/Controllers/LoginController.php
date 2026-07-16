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
            $user = Auth::user();
            if ($user->is_suspended) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->back()->with('error', 'Your account has been suspended. Please contact support.');
            }

            $request->session()->regenerate();

            $redirect = $request->input('redirect');
            if ($redirect && Str::startsWith($redirect, url('/'))) {
                return redirect($redirect)->with('success', 'Logged In successful');
            }

            $default = $user->canAccessAdminPanel()
                ? $user->adminLandingUrl()
                : route('index');

            return redirect()->intended($default)->with('success', 'Logged In successful');
        }

        return redirect()->back()->with('error', 'Invalid email or password');
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
            // Check if email already exists
            if ($validator->errors()->has('email')) {
                $emailError = $validator->errors()->first('email');
                if (str_contains(strtolower($emailError), 'already been taken') || str_contains(strtolower($emailError), 'has already been taken')) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'This email already exists. Please try logging in instead.');
                }
            }
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Validation failed');
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

        $redirect = $request->input('redirect');
        if ($redirect && Str::startsWith($redirect, url('/'))) {
            return redirect($redirect)->with('success', 'Registration successful! Please check your email to verify your account.');
        }

        return redirect()->intended(route('index'))
            ->with('success', 'Registration successful! Please check your email to verify your account.');
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
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Please enter a registered email address.'], 422);
            }
            return redirect()->back()->with('error', 'Please enter a registered email address.');
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Password reset link sent to your email']);
            }
            return redirect()->back()->with('success', 'Password reset link sent to your email');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Unable to send reset link'], 422);
        }
        return redirect()->back()->with('error', 'Unable to send reset link');
    }

    public function showResetForm(Request $request, string $token)
    {
        return view('website.pages.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('index')->with('success', 'Password reset successful. You can sign in now.');
        }

        return redirect()->back()->with('error', __($status))->withInput();
    }

    public function verificationNotice()
    {
        return view('website.pages.verify-email');
    }

    public function resendVerification(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('index')->with('error', 'Please sign in first.');
        }

        if ($user->email_verified_at) {
            return redirect()->route('index')->with('info', 'Email already verified.');
        }

        try {
            Mail::to($user->email)->send(new UserRegistered($user));
        } catch (\Throwable $e) {
            Log::error('Web resend verification failed', ['error' => $e->getMessage()]);
            return redirect()->route('verification.notice')->with('error', 'Could not send verification email.');
        }

        return redirect()->route('verification.notice')->with('success', 'Verification email sent. Check your inbox.');
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
