<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'file_name' => 'sometimes|required|string|max:255',
            'mime_type' => 'nullable|string',
            'file'      => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'file_name.required' => 'File name is required if provided.',
            'file.mimes'         => 'Only JPG, PNG, PDF, DOC, and DOCX files are allowed.',
            'file.max'           => 'File size must not exceed 5MB.',
        ];
    }
}
