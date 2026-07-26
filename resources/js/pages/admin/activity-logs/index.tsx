import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { index } from '@/routes/admin/activity-logs';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import ActivityLogDetailModal from './components/ActivityLogDetailModal';
import type { ActivityLogData, ActivityLogIndexProps } from './types';

function formatDateTime(dateString: string): string {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }) + ' ' + date.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
    });
}

function getModelName(subjectType: string | null): string {
    if (!subjectType) return '—';
    return subjectType.split('\\').pop() ?? subjectType;
}

function getEventBadgeVariant(event: string | null): 'default' | 'secondary' | 'destructive' | 'outline' {
    switch (event) {
        case 'created': return 'default';
        case 'updated': return 'secondary';
        case 'deleted': return 'destructive';
        case 'login': return 'outline';
        case 'logout': return 'outline';
        default: return 'secondary';
    }
}

export default function ActivityLogIndex({
    activityLogs,
    filters,
    eventTypes,
    users,
}: ActivityLogIndexProps) {
    const [selectedLog, setSelectedLog] = useState<ActivityLogData | null>(null);

    const { data, setData, get, processing } = useForm({
        search: filters.search ?? '',
        date_from: filters.date_from ?? '',
        date_to: filters.date_to ?? '',
        user_id: filters.user_id ?? '',
        event: filters.event ?? '',
    });

    function handleFilter() {
        get(index.url(), { preserveState: true });
    }

    function handleReset() {
        setData({
            search: '',
            date_from: '',
            date_to: '',
            user_id: '',
            event: '',
        });
        get(index.url(), { preserveState: true });
    }

    function handlePage(page: number) {
        const params: Record<string, string | number> = { page };
        if (data.search) params.search = data.search;
        if (data.date_from) params.date_from = data.date_from;
        if (data.date_to) params.date_to = data.date_to;
        if (data.user_id) params.user_id = data.user_id;
        if (data.event) params.event = data.event;
        router.get(index.url(), params, { preserveState: true });
    }

    const hasActiveFilters = data.search || data.date_from || data.date_to || data.user_id || data.event;

    return (
        <>
            <Head title="Activity Logs" />

            <h1 className="sr-only">Activity Logs</h1>

            <div className="px-4 py-6 space-y-6">
                <div className="flex items-center justify-between">
                    <Heading
                        variant="small"
                        title="Activity Logs"
                        description="View system activity and audit trail"
                    />
                </div>

                <Card>
                    <CardContent className="pt-6">
                        <form
                            onSubmit={(e: React.FormEvent) => {
                                e.preventDefault();
                                handleFilter();
                            }}
                            className="space-y-4"
                        >
                            <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                                <div className="grid gap-2">
                                    <Label htmlFor="search">Search</Label>
                                    <Input
                                        id="search"
                                        value={data.search}
                                        onChange={(e) => setData('search', e.target.value)}
                                        placeholder="Search description..."
                                    />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="date_from">Date From</Label>
                                    <Input
                                        id="date_from"
                                        type="date"
                                        value={data.date_from}
                                        onChange={(e) => setData('date_from', e.target.value)}
                                    />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="date_to">Date To</Label>
                                    <Input
                                        id="date_to"
                                        type="date"
                                        value={data.date_to}
                                        onChange={(e) => setData('date_to', e.target.value)}
                                    />
                                </div>

                                <div className="grid gap-2">
                                    <Label>User</Label>
                                    <Select
                                        value={data.user_id}
                                        onValueChange={(value) => setData('user_id', value === 'all' ? '' : value)}
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="All users" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="all">All users</SelectItem>
                                            {users.map((user) => (
                                                <SelectItem key={user.id} value={String(user.id)}>
                                                    {user.name}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </div>

                                <div className="grid gap-2">
                                    <Label>Event</Label>
                                    <Select
                                        value={data.event}
                                        onValueChange={(value) => setData('event', value === 'all' ? '' : value)}
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="All events" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="all">All events</SelectItem>
                                            {eventTypes.map((event) => (
                                                <SelectItem key={event} value={event}>
                                                    {event}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>

                            <div className="flex items-center gap-2">
                                <Button type="submit" disabled={processing} size="sm">
                                    Filter
                                </Button>
                                {hasActiveFilters && (
                                    <Button type="button" variant="outline" size="sm" onClick={handleReset}>
                                        Reset
                                    </Button>
                                )}
                            </div>
                        </form>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent className="p-0">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead className="w-[160px]">Date</TableHead>
                                    <TableHead className="w-[100px]">Event</TableHead>
                                    <TableHead className="w-[120px]">User</TableHead>
                                    <TableHead className="w-[100px]">Model</TableHead>
                                    <TableHead>Description</TableHead>
                                    <TableHead className="w-[60px]"></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {activityLogs.data.length === 0 ? (
                                    <TableRow>
                                        <TableCell colSpan={6} className="h-24 text-center text-muted-foreground">
                                            No activity logs found.
                                        </TableCell>
                                    </TableRow>
                                ) : (
                                    activityLogs.data.map((log) => (
                                        <TableRow
                                            key={log.id}
                                            className="cursor-pointer hover:bg-muted/50"
                                            onClick={() => setSelectedLog(log)}
                                        >
                                            <TableCell className="text-xs text-muted-foreground">
                                                {formatDateTime(log.created_at)}
                                            </TableCell>
                                            <TableCell>
                                                {log.event && (
                                                    <Badge variant={getEventBadgeVariant(log.event)} className="text-xs">
                                                        {log.event}
                                                    </Badge>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-sm">
                                                {log.causer?.name ?? '—'}
                                            </TableCell>
                                            <TableCell className="text-sm text-muted-foreground">
                                                {getModelName(log.subject_type)}
                                                {log.subject_id && (
                                                    <span className="text-xs"> #{log.subject_id}</span>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-sm">
                                                {log.description}
                                            </TableCell>
                                            <TableCell>
                                                <Button
                                                    variant="ghost"
                                                    size="sm"
                                                    className="h-7 w-7 p-0"
                                                    onClick={(e) => {
                                                        e.stopPropagation();
                                                        setSelectedLog(log);
                                                    }}
                                                >
                                                    <span className="sr-only">View details</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                )}
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>

                {activityLogs.last_page > 1 && (
                    <div className="flex items-center justify-between">
                        <p className="text-sm text-muted-foreground">
                            Page {activityLogs.current_page} of {activityLogs.last_page} ({activityLogs.total} total)
                        </p>
                        <div className="flex items-center gap-2">
                            <Button
                                variant="outline"
                                size="sm"
                                disabled={activityLogs.current_page <= 1}
                                onClick={() => handlePage(activityLogs.current_page - 1)}
                            >
                                Previous
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                disabled={activityLogs.current_page >= activityLogs.last_page}
                                onClick={() => handlePage(activityLogs.current_page + 1)}
                            >
                                Next
                            </Button>
                        </div>
                    </div>
                )}
            </div>

            <ActivityLogDetailModal
                activityLog={selectedLog}
                open={!!selectedLog}
                onOpenChange={(open) => {
                    if (!open) setSelectedLog(null);
                }}
            />
        </>
    );
}

ActivityLogIndex.layout = {
    breadcrumbs: [
        {
            title: 'Activity Logs',
            href: '/admin/activity-logs',
        },
    ],
};
