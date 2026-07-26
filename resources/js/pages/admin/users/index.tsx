import { Head, Link, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { create, edit, destroy, index, show } from '@/routes/admin/users';
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
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { UserIndexProps } from './types';

function getInitials(name: string): string {
    return name
        .split(' ')
        .map((part) => part[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
}

export default function UserIndex({ users, filters }: UserIndexProps) {
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
            <Head title="Users" />

            <h1 className="sr-only">Users</h1>

            <div className="px-4 py-6 space-y-6">
                <div className="flex items-center justify-between">
                    <Heading
                        variant="small"
                        title="Users"
                        description="Manage user accounts"
                    />

                    <Button asChild>
                        <Link href={create.url()}>Create User</Link>
                    </Button>
                </div>

                <form onSubmit={handleSearch} className="flex items-end gap-4">
                    <div className="grid gap-2 flex-1">
                        <Label htmlFor="search">Search</Label>
                        <Input
                            id="search"
                            value={data.search}
                            onChange={(e) => setData('search', e.target.value)}
                            placeholder="Search users..."
                        />
                        <InputError message={errors.search} />
                    </div>
                    <Button type="submit" disabled={processing}>
                        Search
                    </Button>
                </form>

                <div className="space-y-4">
                    {users.data.length === 0 ? (
                        <p className="text-sm text-muted-foreground">No users found.</p>
                    ) : (
                        users.data.map((user) => (
                            <Card key={user.id}>
                                <CardContent className="flex items-center justify-between py-4">
                                    <div className="flex items-center gap-3">
                                        <Avatar className="size-10">
                                            <AvatarImage src={user.avatar_url ?? undefined} alt={user.name} />
                                            <AvatarFallback>{getInitials(user.name)}</AvatarFallback>
                                        </Avatar>
                                        <div className="space-y-1">
                                            <div className="flex items-center gap-2">
                                                <p className="text-sm font-medium">{user.name}</p>
                                                {!user.is_active && (
                                                    <Badge variant="destructive" className="text-xs">Inactive</Badge>
                                                )}
                                            </div>
                                            <p className="text-xs text-muted-foreground">
                                                {user.email}
                                                {user.roles && user.roles.length > 0 && (
                                                    <> · {user.roles.join(', ')}</>
                                                )}
                                            </p>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <Button variant="outline" size="sm" asChild>
                                            <Link href={show.url(user.id)}>View</Link>
                                        </Button>
                                        <Button variant="outline" size="sm" asChild>
                                            <Link href={edit.url(user.id)}>Edit</Link>
                                        </Button>
                                        <Button
                                            variant="destructive"
                                            size="sm"
                                            onClick={() => setDeleteTarget({ id: user.id, name: user.name })}
                                        >
                                            Delete
                                        </Button>
                                    </div>
                                </CardContent>
                            </Card>
                        ))
                    )}

                    {users.last_page > 1 && (
                        <div className="flex items-center justify-center gap-2">
                            {Array.from({ length: users.last_page }, (_, i) => i + 1).map((page) => (
                                <Button
                                    key={page}
                                    variant={page === users.current_page ? 'default' : 'outline'}
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
                        <AlertDialogTitle>Delete User</AlertDialogTitle>
                        <AlertDialogDescription>
                            Are you sure you want to delete the user "{deleteTarget?.name}"? This action cannot be undone.
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

UserIndex.layout = {
    breadcrumbs: [
        {
            title: 'Users',
            href: '/admin/users',
        },
    ],
};
