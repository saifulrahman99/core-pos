<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;

class ActivityLogService
{
    /**
     * Get paginated activity logs with optional filters.
     *
     * @param  array{search: string, date_from: string|null, date_to: string|null, user_id: string|null, event: string|null}  $filters
     */
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return Activity::query()
            ->with('causer')
            ->when($filters['search'] !== '', function ($query) use ($filters) {
                $query->where(function ($query) use ($filters) {
                    $query->where('description', 'like', "%{$filters['search']}%")
                        ->orWhere('event', 'like', "%{$filters['search']}%");
                });
            })
            ->when($filters['date_from'] !== null, function ($query) use ($filters) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            })
            ->when($filters['date_to'] !== null, function ($query) use ($filters) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            })
            ->when($filters['user_id'] !== null, function ($query) use ($filters) {
                $query->where('causer_id', $filters['user_id']);
            })
            ->when($filters['event'] !== null && $filters['event'] !== '', function ($query) use ($filters) {
                $query->where('event', $filters['event']);
            })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get distinct event types for the filter dropdown.
     *
     * @return Collection<int, string>
     */
    public function getEventTypes(): Collection
    {
        return Activity::whereNotNull('event')
            ->distinct()
            ->pluck('event');
    }

    /**
     * Find an activity log entry by ID.
     */
    public function find(int $id): Activity
    {
        return Activity::with('causer')->findOrFail($id);
    }
}
