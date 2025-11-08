<?php

namespace App\Http\Controllers;

use App\Mail\PartnerRegistration;
use App\Mail\PartnerRequestAdmin;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class PartnerController extends Controller
{
    /**
     * Handle partner request submission from authenticated users.
     */
    public function request(Request $request)
    {
        if (!Auth::check()) {
            abort(403, 'You must be logged in to request partnership.');
        }

        $user = Auth::user();
        $roleName = optional($user->role)->name;

        if (in_array($roleName, ['Super Admin', 'Admin'])) {
            return redirect()->back()->with('info', 'Administrator accounts already have full access.');
        }

        $partnerRole = Role::where('name', 'Partner')->first();

        if (!$partnerRole) {
            Log::warning('Partner role not configured.');
            return redirect()->back()->with('error', 'Partner role is not available. Please contact support.');
        }

        if ($user->role_id === $partnerRole->id) {
            $statusMessage = $user->status == 1
                ? 'You are already an approved partner.'
                : 'Your partner application is currently under review.';

            return redirect()->back()->with('info', $statusMessage);
        }

        $validated = $request->validate([
            'business_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $previousRole = $roleName;
        $user->role_id = $partnerRole->id;
        $user->status = 0; // Pending approval
        $user->save();

        try {
            $approvalUrl = URL::temporarySignedRoute(
                'admin.partners.approve',
                now()->addDays(7),
                ['user' => $user->id]
            );
            $rejectionUrl = URL::temporarySignedRoute(
                'admin.partners.reject',
                now()->addDays(7),
                ['user' => $user->id]
            );

            $adminEmails = User::whereHas('role', function ($query) {
                $query->whereIn('name', ['Super Admin', 'Admin']);
            })->pluck('email')->filter()->unique()->values();

            $configuredAdminEmail = collect([
                env('ADMIN_EMAIL'),
                env('SALES_EMAIL'),
            ])->filter();

            $recipientList = $adminEmails->merge($configuredAdminEmail)->unique()->values();

            if ($recipientList->isNotEmpty()) {
                $primaryRecipient = $recipientList->shift();
                Mail::to($primaryRecipient)
                    ->bcc($recipientList->all())
                    ->send(new PartnerRequestAdmin(
                        $user,
                        $validated['business_name'] ?? null,
                        $validated['notes'] ?? null,
                        $previousRole,
                        $approvalUrl,
                        $rejectionUrl
                    ));
            }

            // Acknowledge user submission
            Mail::to($user->email)->send(new PartnerRegistration($user));
        } catch (\Throwable $th) {
            Log::error('Failed to send partner request emails', [
                'user_id' => $user->id,
                'error' => $th->getMessage(),
            ]);
        }

        return redirect()->back()->with('success', 'Thank you! Your partner request has been submitted. Our team will get back to you shortly.');
    }
}


