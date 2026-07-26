import { type PermissionGroup } from './PermissionMatrix';

export type RoleData = {
    id: number;
    name: string;
    guard_name: string;
    created_at: string | null;
    updated_at: string | null;
    users_count: number;
    permissions: string[] | null;
};

export type RoleProps = {
    data: RoleData;
};

export type PaginatedRoles = {
    data: RoleData[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
};

export type RoleIndexProps = {
    roles: PaginatedRoles;
    filters: {
        search?: string;
        per_page?: number;
    };
};

export type RoleCreateProps = {
    permissions: Record<string, PermissionGroup[]>;
    allPermissionNames: string[];
};

export type RoleEditProps = {
    role: RoleProps;
    permissions: Record<string, PermissionGroup[]>;
    allPermissionNames: string[];
};
