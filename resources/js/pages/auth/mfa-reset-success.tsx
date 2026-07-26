import { Head } from '@inertiajs/react';
import TextLink from '@/components/text-link';
import { login } from '@/routes';

export default function MfaResetSuccess() {
    return (
        <>
            <Head title="MFA Reset Complete" />

            <div className="space-y-6">
                <div className="rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-200">
                    <p>
                        Your two-factor authentication has been successfully disabled.
                        You can now log in using only your email and password.
                    </p>
                </div>

                <div className="text-center text-sm text-muted-foreground">
                    <TextLink href={login()}>Return to log in</TextLink>
                </div>
            </div>
        </>
    );
}

MfaResetSuccess.layout = {
    title: 'MFA Reset Complete',
    description: 'Two-factor authentication has been disabled',
};
