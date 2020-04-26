<?php /** @noinspection ALL */

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Image;
use App\Logic\FilterLogic;
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
    public function store(ProductRequest $validator): JsonResponse
    {
        $data = array_merge($array1 = $validator->validated(), ['slug' => $array1['name']]);
        $product = Product::create($data);
        $product->image = $this->upload($product, $data['image']);

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
     * TODO
     *
     * Create trait for upload image because image update is used
     * more then once in the application and in the same menor.
     *
     * Upload image to public dir.
     *
     * @param Model $model
     * @param UploadedFile $image
     * @return Image
     */
    private function upload(object $object, UploadedFile $file): Image
    {
        return Image::create([
            'imageable_id' => $object->id,
            'imageable_type' => get_class($object),
            'path' => $path = Storage::put('public', $file),
            'url' => url($path)
        ]);
    }
}
