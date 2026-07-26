<?php

namespace App\Policies;

use App\Models\User;

class TwoFactorPolicy
{
    /**
     * Determine whether the user can manage two-factor authentication.
     */
    public function manage(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can enable two-factor authentication.
     */
    public function enable(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can disable two-factor authentication.
     */
    public function disable(User $user): bool
    {
        return ! ($user->hasAnyRole(['admin', 'owner']) && $user->hasEnabledTwoFactorAuthentication());
    }

    /**
     * Determine whether the user can view recovery codes.
     */
    public function viewRecoveryCodes(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can regenerate recovery codes.
     */
    public function regenerateRecoveryCodes(User $user): bool
    {
        return true;
    }
}
