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

export default function AddressForm({ store }: { store: StoreData }) {
    const { data, setData, patch, processing, errors } = useForm({
        address: store.address ?? '',
        google_maps_url: store.google_maps_url ?? '',
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

    return (
        <form onSubmit={submit}>
            <Card>
                <CardHeader>
                    <CardTitle>Address</CardTitle>
                    <CardDescription>
                        Physical location and map link
                    </CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="grid gap-2">
                        <Label htmlFor="address">Full Address</Label>
                        <Textarea
                            id="address"
                            value={data.address}
                            onChange={(e) => setData('address', e.target.value)}
                            placeholder="Full store address"
                            rows={3}
                        />
                        <InputError message={errors.address} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="google_maps_url">Google Maps URL</Label>
                        <Input
                            id="google_maps_url"
                            type="url"
                            value={data.google_maps_url}
                            onChange={(e) => setData('google_maps_url', e.target.value)}
                            placeholder="https://maps.google.com/..."
                        />
                        <InputError message={errors.google_maps_url} />
                    </div>

                    <SaveButton processing={processing} />
                </CardContent>
            </Card>
        </form>
    );
}
