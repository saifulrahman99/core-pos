import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import RoleForm from './components/RoleForm';
import type { RoleEditProps } from './types';

export default function RoleEdit({ role, permissions, allPermissionNames }: RoleEditProps) {
    return (
        <>
            <Head title={`Edit ${role.data.name}`} />

            <h1 className="sr-only">Edit Role</h1>

            <div className="px-4 py-6 space-y-6">
                <Heading
                    variant="small"
                    title={`Edit ${role.data.name}`}
                    description="Update role details and permissions"
                />

                <RoleForm
                    role={role.data}
                    permissions={permissions}
                    allPermissionNames={allPermissionNames}
                />
            </div>
        </>
    );
}

RoleEdit.layout = {
    breadcrumbs: [
        {
            title: 'Roles',
            href: '/admin/roles',
        },
        {
            title: 'Edit',
            href: '/admin/roles/edit',
        },
    ],
};
