<?php

namespace App\Http\Controllers\Api\Products;

use App\Category;
use App\SubCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of product sub categories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Category $category
     * @param SubCategoryRequest $validator
     * @return JsonResponse
     */
    public function store(Category $category, SubCategoryRequest $validator): JsonResponse
    {
        $category->subCategories()->create(
            array_merge($data = $validator->validated(), ['slug' => Str::slug($data['name'])])
        );

        return response()->json(['message' => 'Product sub category has been created']);
    }

    /**
     * Display the specified sub category products.
     *
     * @param SubCategory $subCategory
     * @return JsonResponse
     */
    public function show(SubCategory $subCategory): JsonResponse
    {
        return response()->json([]);
    }

    /**
     * Update the specified sub category in storage.
     *
     * @param SubCategory $subCategory
     * @param SubCategoryRequest $validator
     * @return JsonResponse
     */
    public function update(SubCategory $subCategory, SubCategoryRequest $validator): JsonResponse
    {
        return response()->json();
    }

    /**
     * Remove the specified sub category from storage.
     *
     * @param SubCategory $subCategory
     * @return JsonResponse
     */
    public function destroy(SubCategory $subCategory): JsonResponse
    {
        return response()->json();
    }
}
