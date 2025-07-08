<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->can('assign tasks') ?? false;
    }

    public function rules(): array
    {
        return [
            'name'                => 'sometimes|required|string|max:255',
            'status'              => 'sometimes|required|in:pending,in_progress,completed',
            'priority'            => 'sometimes|required|in:low,medium,high',
            'due_date'            => 'nullable|date',
            'assigned_to_user_id' => 'nullable|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Invalid status.',
        ];
    }
}
