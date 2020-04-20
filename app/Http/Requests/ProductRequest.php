<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'sub_category_id' => ['required', 'integer', 'exists:sub_categories,id'],
            'image' => ['required', 'mimes:jpeg,png,jpg', 'max:2500'],
            'name' => ['required', 'string', 'min:2', 'max:50'],
            'brand' => ['nullable', 'string', 'min:2', 'max:50'],
            'in_stock' => ['required', 'integer'],
            'price' => ['required', 'numeric'],
            'discount' => ['nullable', 'numeric'],
        ];
    }
}
