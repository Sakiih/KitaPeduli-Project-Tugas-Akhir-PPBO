<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('campaigns')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Category::create([
            'name' => trim($validated['name']),
            'slug' => $this->generateUniqueSlug($validated['name']),
            'icon' => $validated['icon'] ?? 'folder-heart',
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function update(StoreCategoryRequest $request, Category $category): RedirectResponse
    {
        $validated = $request->validated();
        if ($validated['name'] !== $category->name) {
            $category->slug = $this->generateUniqueSlug($validated['name'], $category->id);
        }

        $category->name = trim($validated['name']);
        $category->icon = $validated['icon'] ?? $category->icon;
        $category->description = $validated['description'] ?? $category->description;
        $category->save();

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        try {
            $deleted = DB::transaction(function () use ($category): bool {
                $lockedCategory = Category::where('id', $category->id)->lockForUpdate()->firstOrFail();
                if (! $lockedCategory->canBeDeleted()) {
                    return false;
                }

                $lockedCategory->delete();

                return true;
            });
        } catch (QueryException $e) {
            $sqlState = $e->errorInfo[0] ?? $e->getCode();
            $driverCode = (int) ($e->errorInfo[1] ?? 0);
            if ($sqlState === '23000' || in_array($driverCode, [1451, 1452], true)) {
                return back()->with('error', 'Kategori tidak dapat dihapus karena sedang digunakan oleh kampanye.');
            }

            throw $e;
        }

        if (! $deleted) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena sedang digunakan oleh kampanye.');
        }

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name) ?: 'kategori';
        $slug = $baseSlug;
        $counter = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId !== null, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter++;
        }

        return $slug;
    }
}
