import { Head, Link, router, useForm } from '@inertiajs/react';
import { toast } from 'sonner';
import { edit, resetPassword, toggleActive } from '@/routes/admin/users';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { UserShowProps } from './types';

function getInitials(name: string): string {
    return name
        .split(' ')
        .map((part) => part[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
}

export default function UserShow({ user }: UserShowProps) {
    const { data, setData, post, processing, errors } = useForm({
        password: '',
        password_confirmation: '',
    });

    function handleResetPassword(e: React.FormEvent) {
        e.preventDefault();
        post(resetPassword.url(user.data.id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Password reset successfully.');
                setData({ password: '', password_confirmation: '' });
            },
            onError: (errs) => {
                const firstError = Object.values(errs)[0];
                const message = typeof firstError === 'string' ? firstError : 'Failed to reset password.';
                toast.error(message);
            },
        });
    }

    function handleToggleActive() {
        router.post(toggleActive.url(user.data.id), {}, {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('User status updated successfully.');
            },
        });
    }

    return (
        <>
            <Head title={`User: ${user.data.name}`} />

            <h1 className="sr-only">User Details</h1>

            <div className="px-4 py-6 space-y-6">
                <div className="flex items-center justify-between">
                    <Heading
                        variant="small"
                        title={user.data.name}
                        description="User details"
                    />

                    <Button variant="outline" asChild>
                        <Link href={edit.url(user.data.id)}>Edit</Link>
                    </Button>
                </div>

                <Card>
                    <CardContent className="py-6 space-y-6">
                        <div className="flex items-center gap-4">
                            <Avatar className="size-16">
                                <AvatarImage src={user.data.avatar_url ?? undefined} alt={user.data.name} />
                                <AvatarFallback>{getInitials(user.data.name)}</AvatarFallback>
                            </Avatar>
                            <div className="space-y-1">
                                <p className="text-base font-medium">{user.data.name}</p>
                                <p className="text-sm text-muted-foreground">{user.data.email}</p>
                                <div className="flex items-center gap-2">
                                    <Badge variant={user.data.is_active ? 'default' : 'destructive'}>
                                        {user.data.is_active ? 'Active' : 'Inactive'}
                                    </Badge>
                                    {user.data.roles && user.data.roles.length > 0 && (
                                        user.data.roles.map((role) => (
                                            <Badge key={role} variant="secondary">{role}</Badge>
                                        ))
                                    )}
                                </div>
                            </div>
                        </div>

                        <div className="space-y-1">
                            <p className="text-sm text-muted-foreground">
                                Created: {user.data.created_at ? new Date(user.data.created_at).toLocaleDateString() : 'N/A'}
                            </p>
                            <p className="text-sm text-muted-foreground">
                                Last updated: {user.data.updated_at ? new Date(user.data.updated_at).toLocaleDateString() : 'N/A'}
                            </p>
                            <p className="text-sm text-muted-foreground">
                                Email verified: {user.data.email_verified_at ? 'Yes' : 'No'}
                            </p>
                        </div>

                        <div className="flex items-center gap-2">
                            <Button variant={user.data.is_active ? 'destructive' : 'default'} size="sm" onClick={handleToggleActive}>
                                {user.data.is_active ? 'Deactivate' : 'Activate'}
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent className="py-6">
                        <h3 className="text-sm font-medium mb-4">Reset Password</h3>
                        <form onSubmit={handleResetPassword} className="space-y-4">
                            <div className="grid gap-2">
                                <Label htmlFor="password">New Password</Label>
                                <Input
                                    id="password"
                                    type="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    required
                                    placeholder="Minimum 8 characters"
                                />
                                <InputError message={errors.password} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="password_confirmation">Confirm Password</Label>
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    value={data.password_confirmation}
                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                    required
                                    placeholder="Re-enter password"
                                />
                            </div>
                            <Button type="submit" size="sm" disabled={processing}>
                                Reset Password
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

UserShow.layout = {
    breadcrumbs: [
        {
            title: 'Users',
            href: '/admin/users',
        },
        {
            title: 'Details',
            href: '/admin/users/show',
        },
    ],
};
