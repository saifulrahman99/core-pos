export type StoreData = {
    id: number;
    name: string;
    tagline: string | null;
    description: string | null;
    phone: string | null;
    whatsapp: string | null;
    email: string | null;
    website: string | null;
    address: string | null;
    google_maps_url: string | null;
    currency: string;
    timezone: string;
    language: string;
    receipt_header: string | null;
    receipt_footer: string | null;
    opening_time: string | null;
    closing_time: string | null;
    logo_url: string | null;
    cover_image_url: string | null;
    favicon_url: string | null;
};

export type StoreProps = {
    data: StoreData;
};

export type CurrencyOption = {
    value: string;
    label: string;
};

export type LanguageOption = {
    value: string;
    label: string;
};
