<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateStoreRequest;
use App\Http\Resources\StoreResource;
use App\Services\StoreService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class StoreController extends Controller
{
    public function __construct(
        private readonly StoreService $storeService,
    ) {}

    /**
     * Show the store settings page.
     */
    public function edit(): Response
    {
        $store = $this->storeService->get();

        Gate::authorize('view', $store);

        return Inertia::render('settings/store', [
            'store' => new StoreResource($store),
        ]);
    }

    /**
     * Update the store configuration.
     */
    public function update(UpdateStoreRequest $request): RedirectResponse
    {
        $store = $this->storeService->get();

        Gate::authorize('update', $store);

        $files = array_filter([
            'logo' => $request->file('logo'),
            'cover' => $request->file('cover'),
            'favicon' => $request->file('favicon'),
        ]);

        $this->storeService->update($request->validated(), $files);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Store settings updated.')]);

        return to_route('store.edit');
    }
}
