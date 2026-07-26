<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLogResource;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __construct(
        private readonly ActivityLogService $activityLogService,
    ) {}

    public function index(): Response
    {
        Gate::authorize('viewAny', Activity::class);

        $filters = [
            'search' => request('search', ''),
            'date_from' => request('date_from'),
            'date_to' => request('date_to'),
            'user_id' => request('user_id'),
            'event' => request('event'),
        ];

        $activityLogs = $this->activityLogService->paginate(
            filters: $filters,
            perPage: request('per_page', 15),
        );

        return Inertia::render('admin/activity-logs/index', [
            'activityLogs' => ActivityLogResource::collection($activityLogs),
            'filters' => $filters,
            'eventTypes' => $this->activityLogService->getEventTypes(),
            'users' => User::select('id', 'name', 'email')->orderBy('name')->get(),
        ]);
    }
}
