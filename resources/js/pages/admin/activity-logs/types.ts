export type ActivityLogCauser = {
    id: number;
    name: string;
    email: string;
};

export type ActivityLogData = {
    id: number;
    log_name: string | null;
    description: string;
    event: string | null;
    subject_type: string;
    subject_id: number | string;
    causer_type: string | null;
    causer_id: number | string | null;
    properties: Record<string, unknown> | null;
    attribute_changes: Record<string, { old: unknown; new: unknown }> | null;
    created_at: string;
    causer: ActivityLogCauser | null;
};

export type ActivityLogProps = {
    data: ActivityLogData;
};

export type PaginatedActivityLogs = {
    data: ActivityLogData[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
};

export type UserData = {
    id: number;
    name: string;
    email: string;
};

export type ActivityLogIndexProps = {
    activityLogs: PaginatedActivityLogs;
    filters: {
        search?: string;
        date_from?: string | null;
        date_to?: string | null;
        user_id?: string | null;
        event?: string | null;
        per_page?: number;
    };
    eventTypes: string[];
    users: UserData[];
};
