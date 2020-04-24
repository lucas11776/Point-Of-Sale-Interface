<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                'nullable', 'array'
            ],
            'attachments.*' => [
                'required', 'file', 'mimes:jpg,jpeg,png,gif,pdf,docx,mp3,mp4', 'max:' . (100*1000) . ''
            ]
        ];
    }
}
