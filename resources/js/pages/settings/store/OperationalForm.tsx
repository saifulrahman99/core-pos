import { useForm } from '@inertiajs/react';
import { toast } from 'sonner';
import { update } from '@/routes/store';
import InputError from '@/components/input-error';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import SaveButton from './components/SaveButton';
import type { StoreData } from './types';

export default function OperationalForm({ store }: { store: StoreData }) {
    const { data, setData, patch, processing, errors } = useForm({
        opening_time: store.opening_time ?? '',
        closing_time: store.closing_time ?? '',
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
                    <CardTitle>Operational Hours</CardTitle>
                    <CardDescription>
                        Set your store opening and closing times
                    </CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="grid gap-4 sm:grid-cols-2">
                        <div className="grid gap-2">
                            <Label htmlFor="opening_time">Opening Time</Label>
                            <Input
                                id="opening_time"
                                type="time"
                                value={data.opening_time}
                                onChange={(e) => setData('opening_time', e.target.value)}
                            />
                            <InputError message={errors.opening_time} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="closing_time">Closing Time</Label>
                            <Input
                                id="closing_time"
                                type="time"
                                value={data.closing_time}
                                onChange={(e) => setData('closing_time', e.target.value)}
                            />
                            <InputError message={errors.closing_time} />
                        </div>
                    </div>

                    <SaveButton processing={processing} />
                </CardContent>
            </Card>
        </form>
    );
}
