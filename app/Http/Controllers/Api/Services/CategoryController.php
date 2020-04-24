<?php /** @noinspection ALL */

namespace App\Http\Controllers\Api\Services;

use App\Service;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the products category.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        return response()->json();
    }

    /**
     * Store a newly created product category in storage.
     *
     * @param CategoryRequest $validator
     * @return JsonResponse
     */
    public function store(CategoryRequest $validator): JsonResponse
    {
        Category::insert([
            'categorizable_type' => Service::class,
            'name' => $name = $validator->validated()['name'],
            'slug' => Str::slug(strtolower($name))
        ]);

        return response()->json(['message' => 'Service category has been created.']);
    }

    /**
     * Update the specified product category in storage.
     *
     * @param Category $category
     * @param CategoryRequest $validator
     * @return JsonResponse
     */
    public function update(Category $category, CategoryRequest $validator): JsonResponse
    {
        return response()->json();
    }

    /**
     * Remove the specified service category from storage.
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        return response()->json();
    }
}
