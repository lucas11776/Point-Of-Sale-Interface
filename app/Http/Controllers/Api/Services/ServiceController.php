<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers\Api\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Logic\ImageLogic;
use App\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ServiceRequest $validator
     * @param ImageLogic $imageLogic
     * @return JsonResponse
     */
    public function store(ServiceRequest $validator, ImageLogic $imageLogic): JsonResponse
    {
        $service = $this->create($data = $validator->validated());

        $imageLogic->uploadImage($service, $data['image']);

        return response()->json(['message' => 'Service has been created']);
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
     * Create new service in storage.
     *
     * @param array $data
     * @return Service
     */
    protected function create(array $data): Service
    {
        return Service::create([
            'category_id' => $data['category_id'],
            'sub_category_id' => $data['sub_category_id'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'brand' => $data['brand'] ?? null,
            'price' => $data['price'],
            'discount' => $data['discount'] ?? null,
        ]);
    }
}
