import { useForm } from '@inertiajs/react';
import { toast } from 'sonner';
import { update } from '@/routes/store';
import InputError from '@/components/input-error';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import ImagePreview from './components/ImagePreview';
import SaveButton from './components/SaveButton';
import type { StoreData } from './types';

export default function BrandingForm({ store }: { store: StoreData }) {
    const { data, setData, patch, processing, errors, progress } = useForm({
        logo: null as File | null,
        cover: null as File | null,
        favicon: null as File | null,
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();

        patch(update.url(), {
            transform: () => {
                const result: Record<string, File> = {};
                if (data.logo) result.logo = data.logo;
                if (data.cover) result.cover = data.cover;
                if (data.favicon) result.favicon = data.favicon;
                return result;
            },
            preserveScroll: true,
            forceFormData: true,
            onError: (errs) => {
                const firstError = Object.values(errs)[0];
                const message = typeof firstError === 'string' ? firstError : 'Failed to update store settings.';
                toast.error(message);
            },
        });
    }

    return (
        <form onSubmit={submit}>
            <Card>
                <CardHeader>
                    <CardTitle>Branding</CardTitle>
                    <CardDescription>
                        Logo, cover image, and favicon
                    </CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="grid gap-2">
                        <Label htmlFor="logo">Logo</Label>
                        <Input
                            id="logo"
                            type="file"
                            accept="image/*"
                            onChange={(e) => {
                                const file = e.target.files?.[0];
                                if (file) {
                                    setData('logo', file);
                                }
                            }}
                        />
                        <InputError message={errors.logo} />
                        <ImagePreview url={store.logo_url} alt="Store logo" />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="cover">Cover Image</Label>
                        <Input
                            id="cover"
                            type="file"
                            accept="image/*"
                            onChange={(e) => {
                                const file = e.target.files?.[0];
                                if (file) {
                                    setData('cover', file);
                                }
                            }}
                        />
                        <InputError message={errors.cover} />
                        <ImagePreview url={store.cover_image_url} alt="Cover image" />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="favicon">Favicon</Label>
                        <Input
                            id="favicon"
                            type="file"
                            accept="image/*"
                            onChange={(e) => {
                                const file = e.target.files?.[0];
                                if (file) {
                                    setData('favicon', file);
                                }
                            }}
                        />
                        <InputError message={errors.favicon} />
                        <ImagePreview url={store.favicon_url} alt="Favicon" />
                    </div>

                    {progress && (
                        <div className="text-sm text-muted-foreground">
                            Uploading... {progress.percentage}%
                        </div>
                    )}

                    <SaveButton processing={processing} />
                </CardContent>
            </Card>
        </form>
    );
}
