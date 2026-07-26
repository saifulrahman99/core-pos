<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Activitylog\Facades\Activity;

class CategoryService
{
    /**
     * Get paginated categories with search.
     */
    public function paginate(string $search = '', int $perPage = 15): LengthAwarePaginator
    {
        return Category::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->paginate($perPage);
    }

    /**
     * Get a category by ID.
     */
    public function find(int $id): Category
    {
        return Category::findOrFail($id);
    }

    /**
     * Create a new category.
     */
    public function create(array $data): Category
    {
        return DB::transaction(function () use ($data) {
            $category = Category::create([
                'name' => $data['name'],
                'slug' => $data['slug'] ?? Str::slug($data['name']),
                'description' => $data['description'] ?? null,
                'status' => $data['status'] ?? true,
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
                $category->clearMediaCollection('image');
                $category->addMedia($data['image'])->toMediaCollection('image');
                $category->update(['image' => $category->getFirstMediaUrl('image')]);
            }

            Activity::causedBy(auth()->user())->event('category.created')->log("Created category: {$category->name}");

            return $category->fresh();
        });
    }

    /**
     * Update a category.
     */
    public function update(Category $category, array $data): Category
    {
        return DB::transaction(function () use ($category, $data) {
            $updateData = [
                'name' => $data['name'] ?? $category->name,
                'slug' => $data['slug'] ?? $category->slug,
                'description' => $data['description'] ?? $category->description,
                'status' => $data['status'] ?? $category->status,
                'sort_order' => $data['sort_order'] ?? $category->sort_order,
            ];

            $category->update($updateData);

            if (array_key_exists('image', $data)) {
                if ($data['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $category->clearMediaCollection('image');
                    $category->addMedia($data['image'])->toMediaCollection('image');
                    $category->update(['image' => $category->getFirstMediaUrl('image')]);
                } elseif ($data['image'] === null) {
                    $category->clearMediaCollection('image');
                    $category->update(['image' => null]);
                }
            }

            Activity::causedBy(auth()->user())->event('category.updated')->log("Updated category: {$category->name}");

            return $category->fresh();
        });
    }

    /**
     * Delete a category (soft delete).
     */
    public function delete(Category $category): bool
    {
        $name = $category->name;
        $result = $category->delete();

        if ($result) {
            Activity::causedBy(auth()->user())->event('category.deleted')->log("Deleted category: {$name}");
        }

        return $result;
    }
}
