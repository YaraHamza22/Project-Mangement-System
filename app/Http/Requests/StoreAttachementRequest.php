<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttachementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'attachable_id'   => 'required|integer',
            'attachable_type' => 'required|string|in:project,task,comment',
            'file'            => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'A file must be attached.',
            'file.mimes'    => 'Only JPG, PNG, PDF, DOC, and DOCX are allowed.',
            'file.max'      => 'File size must not exceed 5MB.',
        ];
    }
}
