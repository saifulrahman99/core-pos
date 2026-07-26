import { Head, Link, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { create, edit, destroy, index } from '@/routes/admin/roles';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { RoleIndexProps } from './types';

export default function RoleIndex({ roles, filters }: RoleIndexProps) {
    const [deleteTarget, setDeleteTarget] = useState<{ id: number; name: string } | null>(null);

    const { data, setData, get, processing, errors } = useForm({
        search: filters.search ?? '',
    });

    function handleSearch(e: React.FormEvent) {
        e.preventDefault();
        get(index.url(), { preserveState: true });
    }

    function handleDelete() {
        if (deleteTarget) {
            router.delete(destroy.url(deleteTarget.id), { preserveScroll: true });
            setDeleteTarget(null);
        }
    }

    return (
        <>
            <Head title="Roles" />

            <h1 className="sr-only">Roles</h1>

            <div className="px-4 py-6 space-y-6">
                <div className="flex items-center justify-between">
                    <Heading
                        variant="small"
                        title="Roles"
                        description="Manage roles and permissions"
                    />

                    <Button asChild>
                        <Link href={create.url()}>Create Role</Link>
                    </Button>
                </div>

                <form onSubmit={handleSearch} className="flex items-end gap-4">
                    <div className="grid gap-2 flex-1">
                        <Label htmlFor="search">Search</Label>
                        <Input
                            id="search"
                            value={data.search}
                            onChange={(e) => setData('search', e.target.value)}
                            placeholder="Search roles..."
                        />
                        <InputError message={errors.search} />
                    </div>
                    <Button type="submit" disabled={processing}>
                        Search
                    </Button>
                </form>

                <div className="space-y-4">
                    {roles.data.length === 0 ? (
                        <p className="text-sm text-muted-foreground">No roles found.</p>
                    ) : (
                        roles.data.map((role) => (
                            <Card key={role.id}>
                                <CardContent className="flex items-center justify-between py-4">
                                    <div className="space-y-1">
                                        <p className="text-sm font-medium">{role.name}</p>
                                        <p className="text-xs text-muted-foreground">
                                            {role.users_count} user{role.users_count !== 1 ? 's' : ''} assigned
                                            {role.permissions && role.permissions.length > 0 && (
                                                <> · {role.permissions.length} permission{role.permissions.length !== 1 ? 's' : ''}</>
                                            )}
                                        </p>
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <Button variant="outline" size="sm" asChild>
                                            <Link href={edit.url(role.id)}>Edit</Link>
                                        </Button>
                                        <Button
                                            variant="destructive"
                                            size="sm"
                                            onClick={() => setDeleteTarget({ id: role.id, name: role.name })}
                                        >
                                            Delete
                                        </Button>
                                    </div>
                                </CardContent>
                            </Card>
                        ))
                    )}

                    {roles.last_page > 1 && (
                        <div className="flex items-center justify-center gap-2">
                            {Array.from({ length: roles.last_page }, (_, i) => i + 1).map((page) => (
                                <Button
                                    key={page}
                                    variant={page === roles.current_page ? 'default' : 'outline'}
                                    size="sm"
                                    onClick={() => {
                                        router.get(index.url(), { page }, { preserveState: true });
                                    }}
                                >
                                    {page}
                                </Button>
                            ))}
                        </div>
                    )}
                </div>
            </div>

            <AlertDialog open={!!deleteTarget} onOpenChange={(open) => { if (!open) setDeleteTarget(null); }}>
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Delete Role</AlertDialogTitle>
                        <AlertDialogDescription>
                            Are you sure you want to delete the role "{deleteTarget?.name}"? This action cannot be undone.
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel>Cancel</AlertDialogCancel>
                        <AlertDialogAction onClick={handleDelete} className="bg-destructive text-white hover:bg-destructive/90">
                            Delete
                        </AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </>
    );
}

RoleIndex.layout = {
    breadcrumbs: [
        {
            title: 'Roles',
            href: '/admin/roles',
        },
    ],
};
