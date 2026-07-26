import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { toast } from 'sonner';
import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login } from '@/routes';

export default function MfaResetRequest() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        post('/auth/mfa-reset', {
            onSuccess: () => {
                toast.success(
                    'If an account with that email exists and has MFA enabled, a reset link has been sent.',
                );
            },
            onError: () => {
                toast.error('Failed to send reset link. Please check your email address.');
            },
        });
    }

    return (
        <>
            <Head title="Reset MFA" />

            <div className="space-y-6">
                <form onSubmit={handleSubmit}>
                    <div className="space-y-6">
                        <div className="grid gap-2">
                            <Label htmlFor="email">Email address</Label>
                            <Input
                                id="email"
                                type="email"
                                name="email"
                                autoComplete="off"
                                autoFocus
                                placeholder="email@example.com"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                            />

                            <InputError message={errors.email} />
                        </div>

                        <div className="flex items-center justify-start">
                            <Button
                                type="submit"
                                className="w-full"
                                disabled={processing}
                                data-test="send-mfa-reset-link-button"
                            >
                                {processing && (
                                    <LoaderCircle className="h-4 w-4 animate-spin" />
                                )}
                                Send MFA reset link
                            </Button>
                        </div>
                    </div>
                </form>

                <div className="space-x-1 text-center text-sm text-muted-foreground">
                    <span>Or, return to</span>
                    <TextLink href={login()}>log in</TextLink>
                </div>
            </div>
        </>
    );
}

MfaResetRequest.layout = {
    title: 'Reset MFA',
    description: 'Enter your email to receive a MFA reset link',
};
