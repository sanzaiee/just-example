<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShippingAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'min:3', 'max:80'],
            'name' => ['nullable', 'string', 'min:1', 'max:80'],
            'email' => ['nullable', 'string', 'email', 'max:80'],
            'phone' => ['nullable', 'string', 'min:1', 'max:80'],
            'address' => ['required', 'string', 'min:3', 'max:255'],
            'street' => ['nullable', 'string', 'min:1', 'max:80'],
            'city' => ['required', 'string', 'min:1', 'max:80'],
            'tole' => ['nullable', 'string', 'min:1', 'max:80'],
            'house_no' => [
                'nullable',
                'string',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! empty($value) && ! preg_match('/^(apt|suite|floor|unit) [A-Za-z0-9-]+$/i', (string) $value)) {
                        $fail('Must start with apt, suite, floor, or unit.');
                    }
                },
            ],
            'description' => ['nullable', 'string', 'min:1', 'max:255'],
            'postal_code' => [
                'required',
                // 'regex:/^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z] \d[ABCEGHJ-NPRSTV-Z]\d$/i',
            ],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'active' => ['nullable', Rule::in([0, 1])],
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.regex' => 'The postal code must be in the format A1A 1A1.',
        ];
    }
}
