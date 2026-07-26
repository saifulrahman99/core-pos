import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Badge } from '@/components/ui/badge';
import type { ActivityLogData } from '../types';

function formatDateTime(dateString: string): string {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    }) + ' ' + date.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    });
}

function getModelName(subjectType: string | null): string {
    if (!subjectType) return '—';
    return subjectType.split('\\').pop() ?? subjectType;
}

export default function ActivityLogDetailModal({
    activityLog,
    open,
    onOpenChange,
}: {
    activityLog: ActivityLogData | null;
    open: boolean;
    onOpenChange: (open: boolean) => void;
}) {
    if (!activityLog) return null;

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent className="max-w-2xl max-h-[80vh] overflow-y-auto">
                <DialogHeader>
                    <DialogTitle className="flex items-center gap-2">
                        {activityLog.event && (
                            <Badge variant="secondary">{activityLog.event}</Badge>
                        )}
                        {activityLog.description}
                    </DialogTitle>
                    <DialogDescription>
                        {formatDateTime(activityLog.created_at)}
                        {activityLog.causer && (
                            <> · by {activityLog.causer.name}</>
                        )}
                    </DialogDescription>
                </DialogHeader>

                <div className="space-y-4">
                    <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-1">
                            <p className="text-xs font-medium text-muted-foreground">Causer</p>
                            <p className="text-sm">
                                {activityLog.causer
                                    ? `${activityLog.causer.name} (${activityLog.causer.email})`
                                    : '—'}
                            </p>
                        </div>
                        <div className="space-y-1">
                            <p className="text-xs font-medium text-muted-foreground">Subject</p>
                            <p className="text-sm">
                                {getModelName(activityLog.subject_type)}
                                {activityLog.subject_id && (
                                    <span className="text-muted-foreground"> #{activityLog.subject_id}</span>
                                )}
                            </p>
                        </div>
                    </div>

                    <div className="space-y-2">
                        <p className="text-xs font-medium text-muted-foreground">Attribute Changes</p>
                        <pre className="rounded-md bg-muted p-4 text-xs overflow-x-auto whitespace-pre-wrap">
                            {activityLog.attribute_changes
                                ? JSON.stringify(activityLog.attribute_changes, null, 2)
                                : '—'}
                        </pre>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    );
}
