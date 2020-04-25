<?php

namespace App\Http\Requests;

use App\Attachments;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use function foo\func;

class AttachmentsRequest extends FormRequest
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
            'attachments' => [
                Rule::requiredIf(function() {
                    return in_array(Route::current()->uri, ['api/user/attachments/create']);
                }),'nullable', 'array'
            ],
            'attachments.*' => [
                'required', 'file', 'mimes:' . implode(',', Attachments::ALLOWED_EXTENSIONS), 'max:' . (100*1000) . ''
            ]
        ];
    }
}
