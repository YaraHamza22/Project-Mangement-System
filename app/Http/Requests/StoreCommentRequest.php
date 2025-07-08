<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->can('add comments') ?? false;
    }

    public function rules(): array
    {
        return [
            'commentable_id'   => 'required|integer',
            'commentable_type' => 'required|string|in:project,task',
            'content'          => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Comment content is required.',
            'commentable_type.in' => 'Comment must be on a project or a task.',
        ];
    }
}
