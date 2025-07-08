<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
       $user = Auth::user();
       return $user && ($user->hasRole('admin') || $user->hasRole('manager') && $user->can('assign tasks'));
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
}
