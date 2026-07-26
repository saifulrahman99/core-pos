import { Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import UserForm from './components/UserForm';
import type { UserCreateProps } from './types';

export default function UserCreate({ allRoleNames }: UserCreateProps) {
    return (
        <>
            <Head title="Create User" />

            <h1 className="sr-only">Create User</h1>

            <div className="px-4 py-6 space-y-6">
                <Heading
                    variant="small"
                    title="Create User"
                    description="Add a new user account"
                />

                <UserForm allRoleNames={allRoleNames} />
            </div>
        </>
    );
}

UserCreate.layout = {
    breadcrumbs: [
        {
            title: 'Users',
            href: '/admin/users',
        },
        {
            title: 'Create',
            href: '/admin/users/create',
        },
    ],
};
