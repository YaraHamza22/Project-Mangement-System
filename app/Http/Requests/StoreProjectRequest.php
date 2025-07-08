<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->can('manage projects') ?? false;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,pending,completed',
            'due_date'    => 'nullable|date',
            'team_id'     => 'required|exists:teams,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Project name is required.',
            'status.in'         => 'Invalid status value.',
            'team_id.required'  => 'Team selection is required.',
            'team_id.exists'    => 'Selected team does not exist.',
        ];
    }
}
