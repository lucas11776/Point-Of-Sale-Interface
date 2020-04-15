<?php /** @noinspection ALL */

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guest();
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
                'required', 'string', 'email', Rule::unique(User::class)->ignore($this->user)
            ],
            'cellphone_number' => [
                'string', 'min:10', 'max:15', 'numeric', Rule::unique(User::class)->ignore($this->user)
            ],
            'password' => [
                'required', 'string', 'min:8', 'max:20', 'confirmed'
            ]
        ];
    }
}
