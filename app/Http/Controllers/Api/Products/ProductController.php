<?php /** @noinspection ALL */

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Image;
use App\Logic\FilterLogic;
use App\Logic\ImageLogic;
use App\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product): LengthAwarePaginator
    {
        $filter = new FilterLogic($product);

        return $filter->search(['name'])->date()->order('name')->builder()->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $validator
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
            'category_id' => $data['category_id'],
            'sub_category_id' => $data['sub_category_id'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'brand' => $data['brand'] ?? null,
            'in_stock' => $data['in_stock'],
            'price' => $data['price'],
            'discount' => $data['discount'],
            'quantity' => $data['quantity']
        ]);
    }
}
