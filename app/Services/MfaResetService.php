<?php

namespace App\Services;

use App\Mail\MfaResetDisabledMail;
use App\Mail\ResetMfaMail;
use App\Models\MfaResetToken;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MfaResetService
{
    /**
     * Token lifetime in minutes.
     */
    private const TOKEN_LIFETIME_MINUTES = 30;

    /**
     * Request an MFA reset for the given user.
     * Creates a token, sends the reset email.
     */
    public function requestReset(User $user): MfaResetToken
    {
        $token = DB::transaction(function () use ($user): MfaResetToken {
            // Invalidate any existing unused tokens for this user
            MfaResetToken::where('user_id', $user->id)
                ->where('used', false)
                ->update(['used' => true]);

            return MfaResetToken::create([
                'user_id' => $user->id,
                'token' => bin2hex(random_bytes(32)),
                'expires_at' => now()->addMinutes(self::TOKEN_LIFETIME_MINUTES),
                'used' => false,
            ]);
        });

        Mail::to($user)->queue(new ResetMfaMail($user, $token));

        return $token;
    }

    /**
     * Verify the token and disable MFA for the user.
     *
     * @return array{success: bool, message: string}
     */
    public function verifyAndDisable(string $token): array
    {
        $mfaToken = MfaResetToken::where('token', $token)->first();

        if (! $mfaToken) {
            return ['success' => false, 'message' => 'Invalid reset token.'];
        }

        if (! $mfaToken->isValid()) {
            $reason = $mfaToken->used ? 'This token has already been used.' : 'This token has expired.';

            return ['success' => false, 'message' => $reason];
        }

        DB::transaction(function () use ($mfaToken): void {
            // Mark token as used
            $mfaToken->update(['used' => true]);

            // Disable MFA
            $user = $mfaToken->user;
            $user->two_factor_secret = null;
            $user->two_factor_recovery_codes = null;
            $user->two_factor_confirmed_at = null;
            $user->save();
        });

        // Send notification email
        Mail::to($mfaToken->user)->queue(new MfaResetDisabledMail($mfaToken->user));

        return ['success' => true, 'message' => 'MFA has been successfully disabled.'];
    }

    /**
     * Find a user by email who has MFA enabled.
     */
    public function findUserWithMfa(string $email): ?User
    {
        $user = User::where('email', $email)->first();

        if ($user && $user->hasEnabledTwoFactorAuthentication()) {
            return $user;
        }

        return null;
    }
}
