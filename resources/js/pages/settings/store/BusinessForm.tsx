import { useForm } from '@inertiajs/react';
import { toast } from 'sonner';
import { update } from '@/routes/store';
import InputError from '@/components/input-error';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import SaveButton from './components/SaveButton';
import type { CurrencyOption, LanguageOption, StoreData } from './types';

const currencies: CurrencyOption[] = [
    { value: 'IDR', label: 'Indonesian Rupiah (IDR)' },
    { value: 'USD', label: 'US Dollar (USD)' },
    { value: 'EUR', label: 'Euro (EUR)' },
    { value: 'GBP', label: 'British Pound (GBP)' },
    { value: 'MYR', label: 'Malaysian Ringgit (MYR)' },
    { value: 'SGD', label: 'Singapore Dollar (SGD)' },
    { value: 'THB', label: 'Thai Baht (THB)' },
    { value: 'PHP', label: 'Philippine Peso (PHP)' },
    { value: 'VND', label: 'Vietnamese Dong (VND)' },
    { value: 'AUD', label: 'Australian Dollar (AUD)' },
];

const timezones = [
    'Asia/Jakarta',
    'Asia/Makassar',
    'Asia/Jayapura',
    'Asia/Singapore',
    'Asia/Kuala_Lumpur',
    'Asia/Bangkok',
    'Asia/Ho_Chi_Minh',
    'Asia/Manila',
    'Asia/Tokyo',
    'Asia/Seoul',
    'Asia/Shanghai',
    'Asia/Hong_Kong',
    'Australia/Sydney',
    'Australia/Melbourne',
    'Pacific/Auckland',
    'America/New_York',
    'America/Chicago',
    'America/Denver',
    'America/Los_Angeles',
    'Europe/London',
    'Europe/Paris',
    'Europe/Berlin',
    'UTC',
];

const languages: LanguageOption[] = [
    { value: 'id', label: 'Bahasa Indonesia' },
    { value: 'en', label: 'English' },
    { value: 'ms', label: 'Bahasa Melayu' },
    { value: 'th', label: 'Thai' },
    { value: 'vi', label: 'Vietnamese' },
    { value: 'tl', label: 'Filipino' },
];

export default function BusinessForm({ store }: { store: StoreData }) {
    const { data, setData, patch, processing, errors } = useForm({
        currency: store.currency,
        timezone: store.timezone,
        language: store.language,
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
                    <CardTitle>Business</CardTitle>
                    <CardDescription>
                        Currency, timezone, and language settings
                    </CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="grid gap-2">
                        <Label htmlFor="currency">Currency</Label>
                        <select
                            id="currency"
                            value={data.currency}
                            onChange={(e) => setData('currency', e.target.value)}
                            className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-xs transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                        >
                            {currencies.map((c) => (
                                <option key={c.value} value={c.value}>
                                    {c.label}
                                </option>
                            ))}
                        </select>
                        <InputError message={errors.currency} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="timezone">Timezone</Label>
                        <select
                            id="timezone"
                            value={data.timezone}
                            onChange={(e) => setData('timezone', e.target.value)}
                            className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-xs transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                        >
                            {timezones.map((tz) => (
                                <option key={tz} value={tz}>
                                    {tz}
                                </option>
                            ))}
                        </select>
                        <InputError message={errors.timezone} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="language">Language</Label>
                        <select
                            id="language"
                            value={data.language}
                            onChange={(e) => setData('language', e.target.value)}
                            className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-xs transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                        >
                            {languages.map((l) => (
                                <option key={l.value} value={l.value}>
                                    {l.label}
                                </option>
                            ))}
                        </select>
                        <InputError message={errors.language} />
                    </div>

                    <SaveButton processing={processing} />
                </CardContent>
            </Card>
        </form>
    );
}
