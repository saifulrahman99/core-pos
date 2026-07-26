import { Form, Head } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';

type Props = {
    token: string;
    email: string;
};

export default function MfaResetVerify({ token, email }: Props) {
    return (
        <>
            <Head title="Confirm MFA Reset" />

            <div className="space-y-6">
                <div className="rounded-md border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-800 dark:border-yellow-800 dark:bg-yellow-950 dark:text-yellow-200">
                    <p>
                        You are about to disable two-factor authentication for{' '}
                        <strong>{email}</strong>. This will make your account less secure.
                    </p>
                </div>

                <Form action={`/auth/mfa-reset/verify/${token}`} method="post">
                    {({ processing, errors }) => (
                        <>
                            <input type="hidden" name="token" value={token} />

                            <InputError message={errors.token} />

                            <div className="flex items-center justify-start">
                                <Button
                                    type="submit"
                                    variant="destructive"
                                    className="w-full"
                                    disabled={processing}
                                    data-test="confirm-mfa-reset-button"
                                >
                                    {processing && (
                                        <LoaderCircle className="h-4 w-4 animate-spin" />
                                    )}
                                    Disable Two-Factor Authentication
                                </Button>
                            </div>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}

MfaResetVerify.layout = {
    title: 'Confirm MFA Reset',
    description: 'Click the button below to disable two-factor authentication',
};
