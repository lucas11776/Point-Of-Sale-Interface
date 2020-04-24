<?php

namespace App\Http\Requests;

use App\Sale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sales' => [
                'required', 'array'
            ],
            'sales.*' => [
                'required_with:id,type', 'array', 'saleable'
            ],
            'sales.*.id' => [
                'required', 'integer'
            ],
            'sales.*.type' => [
                'required', Rule::in(Sale::SALEABLES)
            ],
            'sales.*.price' => [
                'required', 'numeric'
            ],
            'sales.*.quantity' => [
                'required', 'integer'
            ],
        ];
    }
}
