<?php

namespace Escalated\Laravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEscalationRuleRequest extends FormRequest
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
            'trigger_type' => ['required', 'string'],
            'conditions' => ['required', 'array'],
            'actions' => ['required', 'array'],
            'order' => ['integer'],
            'is_active' => ['boolean'],
        ];
    }
}
