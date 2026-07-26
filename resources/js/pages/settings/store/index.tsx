import { Head } from '@inertiajs/react';
import { edit } from '@/routes/store';
import Heading from '@/components/heading';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AddressForm from './AddressForm';
import BrandingForm from './BrandingForm';
import BusinessForm from './BusinessForm';
import ContactForm from './ContactForm';
import GeneralForm from './GeneralForm';
import OperationalForm from './OperationalForm';
import ReceiptForm from './ReceiptForm';
import type { StoreProps } from './types';

export default function StoreSettings({ store }: { store: StoreProps }) {
    return (
        <>
            <Head title="Store settings" />

            <h1 className="sr-only">Store settings</h1>

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title="Store"
                    description="Manage your store configuration and identity"
                />

                <Tabs defaultValue="general">
                    <TabsList>
                        <TabsTrigger value="general">General</TabsTrigger>
                        <TabsTrigger value="branding">Branding</TabsTrigger>
                        <TabsTrigger value="contact">Contact</TabsTrigger>
                        <TabsTrigger value="address">Address</TabsTrigger>
                        <TabsTrigger value="business">Business</TabsTrigger>
                        <TabsTrigger value="receipt">Receipt</TabsTrigger>
                        <TabsTrigger value="operational">Operational</TabsTrigger>
                    </TabsList>

                    <TabsContent value="general">
                        <GeneralForm store={store.data} />
                    </TabsContent>

                    <TabsContent value="branding">
                        <BrandingForm store={store.data} />
                    </TabsContent>

                    <TabsContent value="contact">
                        <ContactForm store={store.data} />
                    </TabsContent>

                    <TabsContent value="address">
                        <AddressForm store={store.data} />
                    </TabsContent>

                    <TabsContent value="business">
                        <BusinessForm store={store.data} />
                    </TabsContent>

                    <TabsContent value="receipt">
                        <ReceiptForm store={store.data} />
                    </TabsContent>

                    <TabsContent value="operational">
                        <OperationalForm store={store.data} />
                    </TabsContent>
                </Tabs>
            </div>
        </>
    );
}

StoreSettings.layout = {
    breadcrumbs: [
        {
            title: 'Store settings',
            href: edit(),
        },
    ],
};
