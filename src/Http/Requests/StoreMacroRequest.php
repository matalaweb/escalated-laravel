<?php

namespace Escalated\Laravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMacroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'actions' => ['required', 'array', 'min:1'],
            'actions.*.type' => ['required', 'string', Rule::in(['status', 'priority', 'assign', 'tags', 'department', 'reply', 'note'])],
            'actions.*.value' => ['required'],
            'is_shared' => ['boolean'],
            'order' => ['integer', 'min:0'],
        ];
    }
}
