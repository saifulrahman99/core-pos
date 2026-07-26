<?php

namespace App\Actions\Fortify;

class DisableTwoFactorAuthentication extends \Laravel\Fortify\Actions\DisableTwoFactorAuthentication
{
    /**
     * Disable two-factor authentication for the user.
     *
     * Prevents admin and owner roles from disabling 2FA once it is enabled.
     */
    public function __invoke($user): void
    {
        if ($user->hasAnyRole(['admin', 'owner']) && $user->hasEnabledTwoFactorAuthentication()) {
            abort(403, 'Two-factor authentication is mandatory for your role and cannot be disabled.');
        }

        parent::__invoke($user);
    }
}
