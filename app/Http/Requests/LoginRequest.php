<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

     public function rules(): array
    {
        return [
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ];
    }


    public function attributes(): array
    {
        return [
            'email' => 'email address',
            'password' => 'password',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Please enter your password',
        ];
    }
}
