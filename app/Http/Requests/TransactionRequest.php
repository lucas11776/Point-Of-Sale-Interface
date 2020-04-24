<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'transaction' => [
                'nullable', 'array'
            ],
            'transaction.customer' => [
                'nullable', 'integer', 'exists:customers,id'
            ],
            'transaction.payed' => [
                'nullable', 'numeric'
            ],
            'transaction.price' => [
                'nullable', 'numeric'
            ]
        ];
    }
}
