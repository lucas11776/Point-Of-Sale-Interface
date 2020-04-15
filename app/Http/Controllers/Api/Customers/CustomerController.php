<?php

namespace App\Http\Controllers\api\Customers;

use App\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest as CustomerReqest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        return Customer::paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CustomerReqest $validator
     * @return JsonResponse
     */
    public function store(CustomerReqest $validator): JsonResponse
    {
        Customer::create($validator->validated());

        return response()->json(['message' => 'Customer has been created.']);
    }

    /**
     * Display the specified resource.
     *
     * @param Customer $customer
     * @return JsonResponse
     */
    public function show(Customer $customer): JsonResponse
    {
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        return response()->json([]);
    }
}
