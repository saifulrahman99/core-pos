import { useForm } from '@inertiajs/react';
import { toast } from 'sonner';
import { update } from '@/routes/store';
import InputError from '@/components/input-error';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import SaveButton from './components/SaveButton';
import type { StoreData } from './types';

export default function GeneralForm({ store }: { store: StoreData }) {
    const { data, setData, patch, processing, errors } = useForm({
        name: store.name,
        tagline: store.tagline ?? '',
        description: store.description ?? '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();

        patch(update.url(), {
            preserveScroll: true,
            onError: (errs) => {
                const firstError = Object.values(errs)[0];
                const message = typeof firstError === 'string' ? firstError : 'Failed to update store settings.';
                toast.error(message);
            },
        });
    }
    console.log('store', store);

    return (
        <form onSubmit={submit}>
            <Card>
                <CardHeader>
                    <CardTitle>General</CardTitle>
                    <CardDescription>
                        Basic store identity and information
                    </CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="grid gap-2">
                        <Label htmlFor="name">Store Name</Label>
                        <Input
                            id="name"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            required
                            placeholder="Store name"
                        />
                        <InputError message={errors.name} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="tagline">Tagline</Label>
                        <Input
                            id="tagline"
                            value={data.tagline}
                            onChange={(e) => setData('tagline', e.target.value)}
                            placeholder="Short tagline or slogan"
                        />
                        <InputError message={errors.tagline} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="description">Description</Label>
                        <Textarea
                            id="description"
                            value={data.description}
                            onChange={(e) => setData('description', e.target.value)}
                            placeholder="Describe your store"
                            rows={4}
                        />
                        <InputError message={errors.description} />
                    </div>

                    <SaveButton processing={processing} />
                </CardContent>
            </Card>
        </form>
    );
}
