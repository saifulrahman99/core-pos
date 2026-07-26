<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Facades\Activity;

class StoreService
{
    /**
     * Get the singleton store instance, creating a default one if none exists.
     */
    public function get(): Store
    {
        return Store::firstOrCreate([], [
            'name' => config('app.name', 'My Store'),
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
        ]);
    }

    /**
     * Update the store configuration.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, UploadedFile>  $files
     */
    public function update(array $data, array $files = []): Store
    {
        return DB::transaction(function () use ($data, $files) {
            $store = $this->get();

            $store->update($data);

            $this->handleMediaUploads($store, $files);

            Activity::causedBy(auth()->user())->event('store.updated')->log('Updated store settings');

            return $store->fresh();
        });
    }

    /**
     * Handle media file uploads for the store.
     *
     * @param  array<string, UploadedFile>  $files
     */
    private function handleMediaUploads(Store $store, array $files): void
    {
        if (isset($files['logo']) && $files['logo'] instanceof UploadedFile) {
            $store->clearMediaCollection('logo');
            $store->addMedia($files['logo'])->toMediaCollection('logo');
        }

        if (isset($files['cover']) && $files['cover'] instanceof UploadedFile) {
            $store->clearMediaCollection('cover');
            $store->addMedia($files['cover'])->toMediaCollection('cover');
        }

        if (isset($files['favicon']) && $files['favicon'] instanceof UploadedFile) {
            $store->clearMediaCollection('favicon');
            $store->addMedia($files['favicon'])->toMediaCollection('favicon');
        }
    }
}
