<?php /** @noinspection ALL */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ! auth()->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => [
                'required', 'string', 'min:2', 'max:50'
            ],
            'last_name' => [
                'required', 'string', 'min:2', 'max:50'
            ],
            'email' => [
                Rule::requiredIf(function()
                {
                    return $this->json('cellphone_number') ? false : true;
                }
                ), 'string', 'email', Rule::unique(\App\Customer::class)->ignore($this->customer)
            ],
            'cellphone_number' => [
                Rule::requiredIf(function()
                {
                    return $this->json('email') ? false : true;
                }
                ), 'string', 'min:10', 'max:25', Rule::unique(\App\Customer::class)->ignore($this->customer)
            ]
        ];
    }
}
