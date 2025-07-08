<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttachementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
             'file' => ['required', 'file', 'mimes:jpg,png,pdf,docx','max:2048'],
            'attachable_id' => ['required', 'integer'],
            'attachable_type' => ['required', 'string', 'in:App\Models\Project,App\Models\Task,App\Models\Comment'],

        ];
    }
}
