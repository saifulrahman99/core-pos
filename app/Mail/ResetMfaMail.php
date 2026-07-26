<?php

namespace App\Mail;

use App\Models\MfaResetToken;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetMfaMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public MfaResetToken $token,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Your Two-Factor Authentication',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtml(),
        );
    }

    private function buildHtml(): string
    {
        $appName = config('app.name', 'POS');
        $userName = e($this->user->name);
        $resetUrl = route('mfa.reset.verify', ['token' => $this->token->token]);
        $expiresAt = $this->token->expires_at->format('d M Y H:i');
        $currentYear = date('Y');

        return <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Reset Your Two-Factor Authentication</title>
        </head>
        <body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc;">
                <tr>
                    <td align="center" style="padding: 40px 20px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 480px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); overflow: hidden;">
                            <!-- Header -->
                            <tr>
                                <td style="padding: 32px 40px 24px; border-bottom: 1px solid #f1f5f9;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>
                                                <h1 style="margin: 0; font-size: 20px; font-weight: 600; color: #0f172a; letter-spacing: -0.025em;">{$appName}</h1>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Content -->
                            <tr>
                                <td style="padding: 32px 40px;">
                                    <h2 style="margin: 0 0 24px; font-size: 18px; font-weight: 600; color: #0f172a; letter-spacing: -0.025em;">Reset Your Two-Factor Authentication</h2>

                                    <p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #475569;">Hello {$userName},</p>

                                    <p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #475569;">We received a request to reset your two-factor authentication for <strong style="color: #0f172a;">{$appName}</strong>.</p>

                                    <p style="margin: 0 0 24px; font-size: 15px; line-height: 1.6; color: #475569;">Click the button below to reset your MFA. This link will expire in <strong style="color: #0f172a;">30 minutes</strong>.</p>

                                    <!-- CTA Button -->
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                        <tr>
                                            <td align="center">
                                                <a href="{$resetUrl}" style="display: inline-block; background-color: #4f46e5; color: #ffffff; font-size: 14px; font-weight: 600; text-decoration: none; padding: 12px 32px; border-radius: 8px; letter-spacing: 0.025em; transition: background-color 0.2s;">Reset MFA</a>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Divider -->
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                        <tr>
                                            <td style="border-top: 1px solid #e2e8f0;"></td>
                                        </tr>
                                    </table>

                                    <!-- Security Notice -->
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #fefce8; border-radius: 8px; margin-bottom: 0;">
                                        <tr>
                                            <td style="padding: 16px;">
                                                <p style="margin: 0; font-size: 13px; line-height: 1.5; color: #854d0e;">
                                                    <strong>Didn't request this?</strong> If you did not request this reset, please ignore this email. Your MFA will remain active and your account is secure.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td style="padding: 24px 40px; background-color: #f8fafc; border-top: 1px solid #f1f5f9;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>
                                                <p style="margin: 0 0 4px; font-size: 13px; color: #94a3b8;">This is a security notification from {$appName}.</p>
                                                <p style="margin: 0; font-size: 13px; color: #94a3b8;">&copy; {$currentYear} {$appName}. All rights reserved.</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        HTML;
    }
}
