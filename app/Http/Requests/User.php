<?php

namespace App\Http\Requests;

use App\User as UserModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class User extends FormRequest
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
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', Rule::unique(UserModel::class)->ignore($this->user())],
            'cellphone_number' => ['string', 'min:10', 'max:15', 'numeric', Rule::unique(UserModel::class)->ignore($this->user())],
            'password' => ['required', 'string', 'min:8', 'max:20', 'confirmed']
        ];
    }
}
