<?php

namespace App\Http\Controllers\Api\Products;

use App\Product;
use App\Logic\ImageLogic;
use App\Logic\FilterLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a pagination listing of products.
     *
     * @param Product $product
     * @param FilterLogic $filter
     * @return LengthAwarePaginator
     */
    public function index(Product $product, FilterLogic $filter): LengthAwarePaginator
    {
        return $filter->filter($product)
            ->date()->order('name')->search(['name'])->builder()->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $validator
     * @param ImageLogic $imageLogic
     * @return JsonResponse
     */
    public function store(ProductRequest $validator, ImageLogic $imageLogic): JsonResponse
    {
        $data = $validator->validated();

        $imageLogic->uploadImage($this->create($data), $data['image']);

        return response()->json(['message' => 'Product has been added.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Create new product in storage.
     *
     * @param array $data
     * @return Product
     */
    protected function create(array $data): Product
    {
        return Product::create([
            'name' => $data['name'],
            'price' => $data['price'],
            'in_stock' => $data['in_stock'],
            'discount' => $data['discount'],
            'quantity' => $data['quantity'],
            'brand' => $data['brand'] ?? null,
            'slug' => Str::slug($data['name']),
            'category_id' => $data['category_id'],
            'sub_category_id' => $data['sub_category_id'],
        ]);
    }
}
