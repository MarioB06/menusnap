<?php

namespace App\Http\Requests\Dish;

use Illuminate\Foundation\Http\FormRequest;

class StoreDishRequest extends FormRequest
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
            'price' => ['required', 'numeric', 'min:0'],
            'allergens' => ['nullable', 'array'],
            'allergens.*' => ['string'],
            'dietary_tags' => ['nullable', 'array'],
            'dietary_tags.*' => ['string'],
            'is_available' => ['sometimes', 'boolean'],
        ];
    }
}
