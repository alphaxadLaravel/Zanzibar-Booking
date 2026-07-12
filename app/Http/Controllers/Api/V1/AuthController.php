<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\UserRegistered;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:30',
        ]);

        $userRole = Role::whereRaw('LOWER(name) = ?', ['user'])->first()
            ?? Role::where('name', 'User')->first()
            ?? Role::first();

        $user = User::create([
            'firstname' => $validated['first_name'],
            'lastname' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role_id' => $userRole?->id,
            'status' => 1,
        ]);

        try {
            Mail::to($user->email)->send(new UserRegistered($user));
        } catch (\Throwable $e) {
            Log::error('API registration email failed', ['error' => $e->getMessage()]);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user->load('role')),
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'device_name' => 'nullable|string|max:100',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        if ($user->is_suspended) {
            return response()->json([
                'message' => 'Your account has been suspended. Please contact support.',
            ], 403);
        }

        $token = $user->createToken($validated['device_name'] ?? 'mobile')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user->load('role')),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user()->load('role'));
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)], 422);
        }

        return response()->json(['message' => 'Password reset link sent to your email.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)], 422);
        }

        return response()->json(['message' => 'Password has been reset. You can sign in now.']);
    }

    public function resendVerification(Request $request)
    {
        $user = $request->user();

        if ($user->email_verified_at) {
            return response()->json(['message' => 'Your email is already verified.']);
        }

        try {
            Mail::to($user->email)->send(new UserRegistered($user));
        } catch (\Throwable $e) {
            Log::error('API resend verification failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Could not send verification email. Try again later.'], 500);
        }

        return response()->json(['message' => 'Verification email sent. Check your inbox.']);
    }

    public function verifyEmail(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'hash' => 'required|string',
        ]);

        $userId = is_numeric($validated['id']) ? (int) $validated['id'] : null;
        $user = User::findOrFail($userId);

        if (!hash_equals((string) $validated['hash'], sha1($user->email))) {
            return response()->json(['message' => 'Invalid verification link.'], 422);
        }

        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Email already verified.',
                'user' => new UserResource($user->load('role')),
            ]);
        }

        $user->email_verified_at = now();
        $user->save();

        return response()->json([
            'message' => 'Email verified successfully.',
            'user' => new UserResource($user->load('role')),
        ]);
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect.'],
            ]);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return response()->json(['message' => 'Password updated successfully']);
    }
}
