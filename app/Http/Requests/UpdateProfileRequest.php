<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'  => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $this->user()->id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Name is required if provided.',
            'email.required' => 'Email is required if provided.',
            'email.unique'   => 'Email is already in use.',
        ];
    }
}
