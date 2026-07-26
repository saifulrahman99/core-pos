<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TwoFactorService
{
    /**
     * The roles that require mandatory two-factor authentication.
     *
     * @var array<int, string>
     */
    private const MANDATORY_ROLES = [
        'admin',
        'owner',
    ];

    public function __construct(
        private readonly EnableTwoFactorAuthentication $enableAction,
        private readonly ConfirmTwoFactorAuthentication $confirmAction,
        private readonly DisableTwoFactorAuthentication $disableAction,
        private readonly GenerateNewRecoveryCodes $generateRecoveryCodesAction,
    ) {}

    /**
     * Check if two-factor authentication is enabled for the user.
     */
    public function isEnabled(User $user): bool
    {
        return $user->hasEnabledTwoFactorAuthentication();
    }

    /**
     * Check if two-factor authentication is required for the user based on their role.
     */
    public function isRequired(User $user): bool
    {
        return $user->hasAnyRole(self::MANDATORY_ROLES);
    }

    /**
     * Enable two-factor authentication for the user.
     *
     * @return array{secret: string, recoveryCodes: array<int, string>, svg: string}
     */
    public function enable(User $user): array
    {
        ($this->enableAction)($user);

        $user->refresh();

        return [
            'secret' => $user->twoFactorSecret,
            'recoveryCodes' => $user->recoveryCodes(),
            'svg' => $user->twoFactorQrCodeSvg(),
        ];
    }

    /**
     * Confirm two-factor authentication with a valid OTP code.
     *
     * @throws ValidationException
     */
    public function confirm(User $user, string $code): void
    {
        ($this->confirmAction)($user, $code);
    }

    /**
     * Disable two-factor authentication for the user.
     *
     * @throws HttpException
     */
    public function disable(User $user): void
    {
        if ($this->isRequired($user) && $this->isEnabled($user)) {
            abort(403, 'Two-factor authentication is mandatory for your role and cannot be disabled.');
        }

        ($this->disableAction)($user);
    }

    /**
     * Regenerate recovery codes for the user.
     *
     * @return array<int, string>
     */
    public function regenerateRecoveryCodes(User $user): array
    {
        ($this->generateRecoveryCodesAction)($user);

        return $user->fresh()->recoveryCodes();
    }

    /**
     * Get recovery codes for the user.
     *
     * @return array<int, string>
     */
    public function getRecoveryCodes(User $user): array
    {
        return $user->recoveryCodes();
    }

    /**
     * Get the QR code SVG for the user.
     */
    public function getQrCodeSvg(User $user): string
    {
        return $user->twoFactorQrCodeSvg();
    }

    /**
     * Get the security page data for the user.
     *
     * @return array{twoFactorEnabled: bool, requiresConfirmation: bool, isTwoFactorRequired: bool}
     */
    public function getSecurityPageData(User $user): array
    {
        return [
            'twoFactorEnabled' => $this->isEnabled($user),
            'requiresConfirmation' => config('fortify.features', []) !== []
                && in_array('confirm', (array) config('fortify-options.two-factor-authentication', []), true),
            'isTwoFactorRequired' => $this->isRequired($user),
        ];
    }
}
