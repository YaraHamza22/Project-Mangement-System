<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->can('manage projects') ?? false;
    }

    public function rules(): array
    {
        return [
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'sometimes|required|in:active,pending,completed',
            'due_date'    => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Project name is required if provided.',
            'status.in'        => 'Invalid project status.',
        ];
    }
}
