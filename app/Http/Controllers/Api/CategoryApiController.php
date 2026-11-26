<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryApiController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::withCount(['campaigns' => function ($query) {
            $query->where('status', Campaign::STATUS_ACTIVE);
        }])->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori berhasil diambil.',
            'data' => $categories,
        ]);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $slug = $this->generateUniqueSlug($validated['name']);

        $category = Category::create([
            'name' => trim($validated['name']),
            'slug' => $slug,
            'icon' => $validated['icon'] ?? 'folder-heart',
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => $category,
        ], 201);
    }

    public function update(StoreCategoryRequest $request, string $id): JsonResponse
    {
        $category = ctype_digit($id) ? Category::find((int) $id) : null;
        if (! $category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $validated = $request->validated();

        if ($validated['name'] !== $category->name) {
            $category->slug = $this->generateUniqueSlug($validated['name'], $category->id);
        }

        $category->name = trim($validated['name']);
        $category->icon = $validated['icon'] ?? $category->icon;
        $category->description = $validated['description'] ?? $category->description;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui.',
            'data' => $category,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $category = ctype_digit($id) ? Category::find((int) $id) : null;
        if (! $category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan.',
                'data' => null,
            ], 404);
        }

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
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak dapat dihapus karena masih memiliki kampanye terkait.',
                    'data' => null,
                ], 422);
            }

            throw $e;
        }

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak dapat dihapus karena masih memiliki kampanye terkait.',
                'data' => null,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus.',
            'data' => null,
        ]);
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'kategori';
        $slug = $base;
        $counter = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
