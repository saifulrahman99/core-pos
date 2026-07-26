export type CategoryData = {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    image: string | null;
    status: boolean;
    sort_order: number;
    created_at: string;
    updated_at: string;
};

export type CategoryProps = {
    data: CategoryData;
};

export type CategoryIndexProps = {
    categories: {
        data: CategoryData[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    filters: {
        search: string;
        per_page: number;
    };
};