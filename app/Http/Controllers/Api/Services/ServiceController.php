<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers\Api\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Logic\ImageLogic;
use App\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): LengthAwarePaginator
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ServiceRequest $validator
     * @param ImageLogic $image
     * @return JsonResponse
     */
    public function store(ServiceRequest $validator, ImageLogic $image): JsonResponse
    {
        $service = $this->create($data = $validator->validated());

        $image->uploadImage($service, $data['image']);

        return response()->json(['message' => 'Service has been created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        return response()->json([]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        return response()->json([]);
    }

    /**
     * Create new service in storage.
     *
     * @param array $data
     * @return Service
     */
    protected function create(array $data): Service
    {
        return Service::create([
            'name' => $data['name'],
            'price' => $data['price'],
            'brand' => $data['brand'] ?? null,
            'slug' => Str::slug($data['name']),
            'category_id' => $data['category_id'],
            'discount' => $data['discount'] ?? null,
            'sub_category_id' => $data['sub_category_id'],
        ]);
    }
}
