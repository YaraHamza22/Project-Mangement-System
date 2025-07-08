<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->can('assign tasks') ?? false;
    }

    public function rules(): array
    {
        return [
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'status'              => 'required|in:pending,in_progress,completed',
            'priority'            => 'required|in:low,medium,high',
            'due_date'            => 'nullable|date',
            'project_id'          => 'required|exists:projects,id',
            'assigned_to_user_id' => 'nullable|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Task name is required.',
            'status.in'         => 'Status must be one of: pending, in_progress, completed.',
            'priority.in'       => 'Priority must be one of: low, medium, high.',
        ];
    }
}
    