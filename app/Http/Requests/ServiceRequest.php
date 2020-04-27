<?php /** @noinspection PhpUndefinedFieldInspection */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
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
            'image' => [
                'required', 'mimes:jpeg,png,jpg', 'max:2500'
            ],
            'category_id' => [
                'required', 'integer', Rule::exists('categories', 'id')
            ],
            'sub_category_id' => [
                'required', 'integer', Rule::exists('sub_categories', 'id')
            ],
            'name' => [
                'required', 'string', 'min:3', 'max:150', Rule::unique('services')->ignore($this->service)
            ],
            'brand' => [
                'required', 'string', 'min:3', 'max:150'
            ],
            'price' => [
                'required', 'numeric'
            ],
            'discount' => [
                'nullable', 'numeric'
            ]
        ];
    }
}
