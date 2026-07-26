import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import UserForm from './components/UserForm';
import type { UserEditProps } from './types';

export default function UserEdit({ user, allRoleNames }: UserEditProps) {
    return (
        <>
            <Head title={`Edit ${user.data.name}`} />

            <h1 className="sr-only">Edit User</h1>

            <div className="px-4 py-6 space-y-6">
                <Heading
                    variant="small"
                    title={`Edit ${user.data.name}`}
                    description="Update user details"
                />

                <UserForm user={user.data} allRoleNames={allRoleNames} />
            </div>
        </>
    );
}

UserEdit.layout = {
    breadcrumbs: [
        {
            title: 'Users',
            href: '/admin/users',
        },
        {
            title: 'Edit',
            href: '/admin/users/edit',
        },
    ],
};
