import { Head } from '@inertiajs/react';
import TextLink from '@/components/text-link';
import { login } from '@/routes';

export default function MfaResetInvalid() {
    return (
        <>
            <Head title="Invalid Reset Link" />

            <div className="space-y-6">
                <div className="rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200">
                    <p>
                        This MFA reset link is invalid or has expired.
                        Please request a new one.
                    </p>
                </div>

                <div className="text-center text-sm text-muted-foreground">
                    <TextLink href={login()}>Return to log in</TextLink>
                </div>
            </div>
        </>
    );
}

MfaResetInvalid.layout = {
    title: 'Invalid Reset Link',
    description: 'This MFA reset link is invalid or has expired',
};
