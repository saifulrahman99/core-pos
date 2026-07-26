export type UserData = {
    id: number;
    name: string;
    email: string;
    is_active: boolean;
    email_verified_at: string | null;
    avatar_url: string | null;
    created_at: string | null;
    updated_at: string | null;
    roles: string[] | null;
};

export type UserProps = {
    data: UserData;
};

export type PaginatedUsers = {
    data: UserData[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
};

export type UserIndexProps = {
    users: PaginatedUsers;
    filters: {
        search?: string;
        per_page?: number;
    };
};

export type UserCreateProps = {
    allRoleNames: string[];
};

export type UserEditProps = {
    user: UserProps;
    allRoleNames: string[];
};

export type UserShowProps = {
    user: UserProps;
};
