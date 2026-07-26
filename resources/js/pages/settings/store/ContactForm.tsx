import { useForm } from '@inertiajs/react';
import { toast } from 'sonner';
import { update } from '@/routes/store';
import InputError from '@/components/input-error';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import SaveButton from './components/SaveButton';
import type { StoreData } from './types';

export default function ContactForm({ store }: { store: StoreData }) {
    const { data, setData, patch, processing, errors } = useForm({
        phone: store.phone ?? '',
        whatsapp: store.whatsapp ?? '',
        email: store.email ?? '',
        website: store.website ?? '',
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
                    <CardTitle>Contact</CardTitle>
                    <CardDescription>
                        How customers can reach you
                    </CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="grid gap-2">
                        <Label htmlFor="phone">Phone</Label>
                        <Input
                            id="phone"
                            value={data.phone}
                            onChange={(e) => setData('phone', e.target.value)}
                            placeholder="+62 812 3456 7890"
                        />
                        <InputError message={errors.phone} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="whatsapp">WhatsApp</Label>
                        <Input
                            id="whatsapp"
                            value={data.whatsapp}
                            onChange={(e) => setData('whatsapp', e.target.value)}
                            placeholder="+62 812 3456 7890"
                        />
                        <InputError message={errors.whatsapp} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="email">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            placeholder="info@store.com"
                        />
                        <InputError message={errors.email} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="website">Website</Label>
                        <Input
                            id="website"
                            type="url"
                            value={data.website}
                            onChange={(e) => setData('website', e.target.value)}
                            placeholder="https://store.com"
                        />
                        <InputError message={errors.website} />
                    </div>

                    <SaveButton processing={processing} />
                </CardContent>
            </Card>
        </form>
    );
}
