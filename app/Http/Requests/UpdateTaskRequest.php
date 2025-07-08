<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
           return Auth::user()?->can('assign tasks');
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,in_progress,completed'],
            'priority' => ['nullable', 'in:low,medium,high'],
            'due_date' => ['nullable', 'date'],
            'project_id' => ['required', 'exists:projects,id'],
            'assigned_to_user_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
