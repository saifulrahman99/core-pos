import { useForm } from '@inertiajs/react';
import { toast } from 'sonner';
import { update } from '@/routes/store';
import InputError from '@/components/input-error';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import SaveButton from './components/SaveButton';
import type { StoreData } from './types';

export default function ReceiptForm({ store }: { store: StoreData }) {
    const { data, setData, patch, processing, errors } = useForm({
        receipt_header: store.receipt_header ?? '',
        receipt_footer: store.receipt_footer ?? '',
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
                    <CardTitle>Receipt</CardTitle>
                    <CardDescription>
                        Customize receipt header and footer text
                    </CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="grid gap-2">
                        <Label htmlFor="receipt_header">Receipt Header</Label>
                        <Input
                            id="receipt_header"
                            value={data.receipt_header}
                            onChange={(e) => setData('receipt_header', e.target.value)}
                            placeholder="Header text shown on receipts"
                        />
                        <InputError message={errors.receipt_header} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="receipt_footer">Receipt Footer</Label>
                        <Input
                            id="receipt_footer"
                            value={data.receipt_footer}
                            onChange={(e) => setData('receipt_footer', e.target.value)}
                            placeholder="Footer text shown on receipts"
                        />
                        <InputError message={errors.receipt_footer} />
                    </div>

                    <SaveButton processing={processing} />
                </CardContent>
            </Card>
        </form>
    );
}
