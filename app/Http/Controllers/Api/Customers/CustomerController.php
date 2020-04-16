<?php /** @noinspection ALL */

namespace App\Http\Controllers\api\Customers;

use App\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     *
     * @return LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        return Customer::paginate();
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param CustomerRequest $validator
     * @return JsonResponse
     */
    public function store(CustomerRequest $validator): JsonResponse
    {
        Customer::create($validator->validated());

        return response()->json(['message' => 'Customer has been created.']);
    }

    /**
     * Display the specified customer.
     *
     * @param Customer $customer
     * @return JsonResponse
     */
    public function show(Customer $customer): JsonResponse
    {
        return response()->json($customer);
    }

    /**
     * Update the specified customer in storage.
     *
     * @param Customer $customer
     * @param CustomerRequest $validate
     * @return JsonResponse
     */
    public function update(Customer $customer, CustomerRequest $validate): JsonResponse
    {
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Customer $customer): JsonResponse
    {
        return response()->json([]);
    }
}
