<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MfaResetToken;
use App\Services\MfaResetService;
use Inertia\Inertia;

class MfaResetVerifyController extends Controller
{
    public function __construct(
        private readonly MfaResetService $mfaResetService,
    ) {}

    /**
     * Show the MFA reset confirmation page.
     */
    public function show(string $token)
    {
        $mfaToken = MfaResetToken::where('token', $token)->first();

        if (! $mfaToken || ! $mfaToken->isValid()) {
            return Inertia::render('auth/mfa-reset-invalid');
        }

        return Inertia::render('auth/mfa-reset-verify', [
            'token' => $token,
            'email' => $mfaToken->user->email,
        ]);
    }

    /**
     * Verify the token and disable MFA.
     */
    public function store(string $token)
    {
        $result = $this->mfaResetService->verifyAndDisable($token);

        if ($result['success']) {
            return redirect()->route('mfa.reset.success');
        }

        return back()->withErrors(['token' => $result['message']]);
    }

    /**
     * Show the success page.
     */
    public function success()
    {
        return Inertia::render('auth/mfa-reset-success');
    }
}
