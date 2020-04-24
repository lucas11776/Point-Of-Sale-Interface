<?php

namespace App\Http\Controllers\Api\Products;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Product;
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
     * Store a newly created product category in storage.
     *
     * @param CategoryRequest $validator
     * @return JsonResponse
     */
    public function store(CategoryRequest $validator): JsonResponse
    {
        Category::create([
            'categorizable_type' => Product::class,
            'name' => $name = $validator->validated()['name'],
            'slug' => Str::slug(strtolower($name))
        ]);

        return response()->json(['message' => 'Product category has been created.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Update the specified product category in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified product category from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
