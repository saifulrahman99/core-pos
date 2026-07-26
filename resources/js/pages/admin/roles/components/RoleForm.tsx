import { useForm } from '@inertiajs/react';
import { toast } from 'sonner';
import { store, update } from '@/routes/admin/roles';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import PermissionMatrix from './PermissionMatrix';
import type { RoleData } from '../types';

type PermissionItem = {
    id: number;
    name: string;
    guard_name: string;
};

type RoleFormProps = {
    role?: RoleData;
    permissions: Record<string, PermissionItem[]>;
    allPermissionNames: string[];
};

export default function RoleForm({ role, permissions, allPermissionNames }: RoleFormProps) {
    const isEdit = !!role;

    const { data, setData, post, patch, processing, errors } = useForm({
        name: role?.name ?? '',
        permissions: role?.permissions ?? ([] as string[]),
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();

        if (isEdit) {
            patch(update.url(role.id), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Role updated successfully.');
                },
                onError: (errs) => {
                    const firstError = Object.values(errs)[0];
                    const message = typeof firstError === 'string' ? firstError : 'Failed to update role.';
                    toast.error(message);
                },
            });
        } else {
            post(store.url(), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Role created successfully.');
                },
                onError: (errs) => {
                    const firstError = Object.values(errs)[0];
                    const message = typeof firstError === 'string' ? firstError : 'Failed to create role.';
                    toast.error(message);
                },
            });
        }
    }

    return (
        <form onSubmit={submit} className="space-y-6">
            <div className="grid gap-2">
                <Label htmlFor="name">Role Name</Label>
                <Input
                    id="name"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    required
                    placeholder="e.g. Admin, Cashier, Kitchen"
                />
                <InputError message={errors.name} />
            </div>

            <PermissionMatrix
                permissions={permissions}
                selected={data.permissions}
                onChange={(permissions) => setData('permissions', permissions)}
                errors={errors.permissions}
            />

            <div className="flex items-center gap-4">
                <Button type="submit" disabled={processing}>
                    {isEdit ? 'Update Role' : 'Create Role'}
                </Button>
            </div>
        </form>
    );
}
