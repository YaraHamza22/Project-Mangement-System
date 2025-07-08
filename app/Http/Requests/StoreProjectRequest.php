<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        $user = Auth::user();
        return $user && ($user->hasRole('admin') || $user->hasRole('manager') && $user->can('manage project'));
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
}
