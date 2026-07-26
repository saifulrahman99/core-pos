import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import RoleForm from './components/RoleForm';
import type { RoleCreateProps } from './types';

export default function RoleCreate({ permissions, allPermissionNames }: RoleCreateProps) {
    return (
        <>
            <Head title="Create Role" />

            <h1 className="sr-only">Create Role</h1>

            <div className="px-4 py-6 space-y-6">
                <Heading
                    variant="small"
                    title="Create Role"
                    description="Define a new role with permissions"
                />

                <RoleForm
                    permissions={permissions}
                    allPermissionNames={allPermissionNames}
                />
            </div>
        </>
    );
}

RoleCreate.layout = {
    breadcrumbs: [
        {
            title: 'Roles',
            href: '/admin/roles',
        },
        {
            title: 'Create',
            href: '/admin/roles/create',
        },
    ],
};
