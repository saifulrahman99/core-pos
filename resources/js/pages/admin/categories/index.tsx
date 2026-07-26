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
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { ImageIcon, ArrowUpDown, CalendarIcon } from 'lucide-react';

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
                            <Card key={category.id} className="overflow-hidden">
                                <CardHeader className="p-4">
                                    <div className="flex items-start gap-4">
                                        {/* Image thumbnail */}
                                        <div className="relative h-20 w-20 flex-shrink-0 rounded-lg border bg-muted overflow-hidden">
                                            {category.image ? (
                                                <img
                                                    src={category.image}
                                                    alt={category.name}
                                                    className="h-full w-full object-cover"
                                                />
                                            ) : (
                                                <div className="flex h-full items-center justify-center">
                                                    <ImageIcon className="h-8 w-8 text-muted-foreground" />
                                                </div>
                                            )}
                                        </div>
                                        
                                        {/* Category info */}
                                        <div className="flex-1 min-w-0">
                                            <div className="flex items-center gap-2">
                                                <CardTitle className="text-lg font-medium">{category.name}</CardTitle>
                                                <Badge variant={category.status ? 'default' : 'secondary'}>
                                                    {category.status ? 'Active' : 'Inactive'}
                                                </Badge>
                                            </div>
                                            
                                            <div className="mt-2 flex flex-wrap items-center gap-4 text-sm text-muted-foreground">
                                                <span className="flex items-center gap-1">
                                                    <ArrowUpDown className="h-3 w-3" />
                                                    Sort: {category.sort_order}
                                                </span>
                                                <span className="flex items-center gap-1">
                                                    <CalendarIcon className="h-3 w-3" />
                                                    Created: {new Date(category.created_at).toLocaleDateString()}
                                                </span>
                                            </div>
                                            
                                            {category.description && (
                                                <p className="mt-2 text-sm text-muted-foreground line-clamp-2">
                                                    {category.description}
                                                </p>
                                            )}
                                            
                                            <p className="mt-1 text-xs text-muted-foreground font-mono">
                                                Slug: {category.slug}
                                            </p>
                                        </div>
                                        
                                        {/* Actions */}
                                        <div className="flex flex-col items-end gap-2">
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
                                    </div>
                                </CardHeader>
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