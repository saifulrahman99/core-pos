import { Head, Link, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { create, edit, destroy } from '@/routes/admin/categories';
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

import type { CategoryIndexProps } from './types';

export default function CategoryIndex({ categories, filters }: CategoryIndexProps) {
    const [deleteTarget, setDeleteTarget] = useState<{ id: number; name: string } | null>(null);

    const { data, setData, get, processing, errors } = useForm({
        search: filters.search ?? '',
    });

    function handleSearch(e: React.FormEvent) {
        e.preventDefault();
        get(create.index.url(), { preserveState: true });
    }

    function handleDelete() {
        if (deleteTarget) {
            router.delete(destroy.url(deleteTarget.id), { preserveScroll: true });
            setDeleteTarget(null);
        }
    }

    return (
        <>
            <Head title="Categories" />

            <h1 className="sr-only">Categories</h1>

            <div className="px-4 py-6 space-y-6">
                <div className="flex items-center justify-between">
                    <Heading
                        variant="small"
                        title="Categories"
                        description="Manage product categories"
                    />

                    <Button asChild>
                        <Link href={create.url()}>Create Category</Link>
                    </Button>
                </div>

                <form onSubmit={handleSearch} className="flex items-end gap-4">
                    <div className="grid gap-2 flex-1">
                        <Label htmlFor="search">Search</Label>
                        <Input
                            id="search"
                            value={data.search}
                            onChange={(e) => setData('search', e.target.value)}
                            placeholder="Search categories..."
                        />
                        <InputError message={errors.search} />
                    </div>
                    <Button type="submit" disabled={processing}>
                        Search
                    </Button>
                </form>

                <div className="space-y-4">
                    {categories.data.length === 0 ? (
                        <p className="text-sm text-muted-foreground">No categories found.</p>
                    ) : (
                        categories.data.map((category) => (
                            <Card key={category.id}>
                                <CardContent className="flex items-center justify-between py-4">
                                    <div className="space-y-1">
                                        <p className="text-sm font-medium">{category.name}</p>
                                        <p className="text-xs text-muted-foreground">
                                            {category.slug}
                                            {category.description && (
                                                <> · {category.description}</>
                                            )}
                                        </p>
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <Button variant="outline" size="sm" asChild>
                                            <Link href={edit.url(category.id)}>Edit</Link>
                                        </Button>
                                        <Button
                                            variant="destructive"
                                            size="sm"
                                            onClick={() => setDeleteTarget({ id: category.id, name: category.name })}
                                        >
                                            Delete
                                        </Button>
                                    </div>
                                </CardContent>
                            </Card>
                        ))
                    )}

                    {categories.last_page > 1 && (
                        <div className="flex items-center justify-center gap-2">
                            {Array.from({ length: categories.last_page }, (_, i) => i + 1).map((page) => (
                                <Button
                                    key={page}
                                    variant={page === categories.current_page ? 'default' : 'outline'}
                                    size="sm"
                                    onClick={() => {
                                        router.get(create.index.url(), { page }, { preserveState: true });
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
                        <AlertDialogTitle>Delete Category</AlertDialogTitle>
                        <AlertDialogDescription>
                            Are you sure you want to delete the category "{deleteTarget?.name}"? This action cannot be undone.
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

CategoryIndex.layout = {
    breadcrumbs: [
        {
            title: 'Categories',
            href: '/admin/categories',
        },
    ],
};