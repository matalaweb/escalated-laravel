<?php

namespace Escalated\Laravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSlaPolicyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_default' => ['boolean'],
            'first_response_hours' => ['required', 'array'],
            'resolution_hours' => ['required', 'array'],
            'business_hours_only' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }
}
