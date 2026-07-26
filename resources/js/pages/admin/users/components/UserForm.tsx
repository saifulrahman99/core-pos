import { useForm } from '@inertiajs/react';
import { toast } from 'sonner';
import { store, update } from '@/routes/admin/users';
import InputError from '@/components/input-error';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { UserData } from '../types';

type UserFormProps = {
    user?: UserData;
    allRoleNames: string[];
};

function getInitials(name: string): string {
    return name
        .split(' ')
        .map((part) => part[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
}

export default function UserForm({ user, allRoleNames }: UserFormProps) {
    const isEdit = !!user;

    const { data, setData, post, patch, processing, errors } = useForm({
        name: user?.name ?? '',
        email: user?.email ?? '',
        password: '',
        password_confirmation: '',
        is_active: user?.is_active ?? true,
        roles: user?.roles ?? ([] as string[]),
        avatar: null as File | null,
    });

    function handleFileChange(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0] ?? null;
        setData('avatar', file);
    }

    function submit(e: React.FormEvent) {
        e.preventDefault();

        if (isEdit) {
            patch(update.url(user.id), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('User updated successfully.');
                },
                onError: (errs) => {
                    const firstError = Object.values(errs)[0];
                    const message = typeof firstError === 'string' ? firstError : 'Failed to update user.';
                    toast.error(message);
                },
            });
        } else {
            post(store.url(), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('User created successfully.');
                },
                onError: (errs) => {
                    const firstError = Object.values(errs)[0];
                    const message = typeof firstError === 'string' ? firstError : 'Failed to create user.';
                    toast.error(message);
                },
            });
        }
    }

    function handleRoleToggle(roleName: string) {
        const current = data.roles;
        if (current.includes(roleName)) {
            setData('roles', current.filter((r) => r !== roleName));
        } else {
            setData('roles', [...current, roleName]);
        }
    }

    return (
        <form onSubmit={submit} className="space-y-6">
            <div className="flex items-center gap-4">
                <div className="relative">
                    <Avatar className="size-16">
                        {data.avatar ? (
                            <AvatarImage src={URL.createObjectURL(data.avatar)} alt={data.name} />
                        ) : (
                            <AvatarImage src={user?.avatar_url ?? undefined} alt={data.name} />
                        )}
                        <AvatarFallback>{getInitials(data.name || 'U')}</AvatarFallback>
                    </Avatar>
                </div>
                <div className="space-y-1">
                    <input
                        type="file"
                        accept="image/jpeg,image/png"
                        className="hidden"
                        id="avatar-upload"
                        onChange={handleFileChange}
                    />
                    <Button type="button" variant="outline" size="sm" onClick={() => document.getElementById('avatar-upload')?.click()}>
                        Choose Image
                    </Button>
                    {data.avatar && (
                        <Button type="button" variant="ghost" size="sm" onClick={() => { setData('avatar', null); const input = document.getElementById('avatar-upload') as HTMLInputElement | null; if (input) input.value = ''; }}>
                            Remove
                        </Button>
                    )}
                    <p className="text-xs text-muted-foreground">JPG, PNG up to 2MB</p>
                </div>
                <InputError message={errors.avatar} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="name">Name</Label>
                <Input
                    id="name"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    required
                    placeholder="Full name"
                />
                <InputError message={errors.name} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="email">Email</Label>
                <Input
                    id="email"
                    type="email"
                    value={data.email}
                    onChange={(e) => setData('email', e.target.value)}
                    required
                    placeholder="user@example.com"
                />
                <InputError message={errors.email} />
            </div>

            {!isEdit && (
                <>
                    <div className="grid gap-2">
                        <Label htmlFor="password">Password</Label>
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
                </>
            )}

            <div className="flex items-center gap-2">
                <Checkbox
                    id="is_active"
                    checked={data.is_active}
                    onCheckedChange={(checked) => setData('is_active', checked === true)}
                />
                <Label htmlFor="is_active" className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                    Active
                </Label>
            </div>

            {allRoleNames.length > 0 && (
                <div className="space-y-2">
                    <Label>Roles</Label>
                    <div className="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4">
                        {allRoleNames.map((roleName) => (
                            <div key={roleName} className="flex items-center gap-2">
                                <Checkbox
                                    id={`role-${roleName}`}
                                    checked={data.roles.includes(roleName)}
                                    onCheckedChange={() => handleRoleToggle(roleName)}
                                />
                                <Label htmlFor={`role-${roleName}`} className="text-sm font-normal leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    {roleName}
                                </Label>
                            </div>
                        ))}
                    </div>
                    <InputError message={errors.roles} />
                </div>
            )}

            <div className="flex items-center gap-4">
                <Button type="submit" disabled={processing}>
                    {isEdit ? 'Update User' : 'Create User'}
                </Button>
            </div>
        </form>
    );
}
