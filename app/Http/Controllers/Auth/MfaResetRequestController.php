<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\MfaResetService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MfaResetRequestController extends Controller
{
    public function __construct(
        private readonly MfaResetService $mfaResetService,
    ) {}

    /**
     * Show the form to request an MFA reset.
     */
    public function show()
    {
        return Inertia::render('auth/mfa-reset-request');
    }

    /**
     * Handle the MFA reset request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = $this->mfaResetService->findUserWithMfa($request->email);

        if (! $user) {
            // Don't reveal whether the email exists or has MFA enabled
            return back()->with('status', 'If an account with that email exists and has MFA enabled, a reset link has been sent.');
        }

        $this->mfaResetService->requestReset($user);

        return back()->with('status', 'If an account with that email exists and has MFA enabled, a reset link has been sent.');
    }
}
