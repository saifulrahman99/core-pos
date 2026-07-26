<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService,
    ) {}

    public function index(): Response
    {
        Gate::authorize('viewAny', Category::class);

        $categories = $this->categoryService->paginate(
            search: request('search', ''),
            perPage: request('per_page', 15),
        );

        return Inertia::render('admin/categories/index', [
            'categories' => CategoryResource::collection($categories),
            'filters' => request(['search', 'per_page']),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Category::class);

        return Inertia::render('admin/categories/create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);

        $data = $request->validated();

        if (isset($data['image'])) {
            unset($data['image']);
        }

        $this->categoryService->create($request->all());

        return to_route('admin.categories.index')
            ->with('toast', ['type' => 'success', 'message' => 'Category created successfully.']);
    }

    public function edit(Category $category): Response
    {
        Gate::authorize('update', $category);

        return Inertia::render('admin/categories/edit', [
            'category' => new CategoryResource($category),
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        Gate::authorize('update', $category);

        $this->categoryService->update($category, $request->validated());

        return to_route('admin.categories.index')
            ->with('toast', ['type' => 'success', 'message' => 'Category updated successfully.']);
    }

    public function destroy(Category $category): RedirectResponse
    {
        Gate::authorize('delete', $category);

        $this->categoryService->delete($category);

        return to_route('admin.categories.index')
            ->with('toast', ['type' => 'success', 'message' => 'Category deleted successfully.']);
    }
}
