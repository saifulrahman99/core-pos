<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $nullableFields = [
            'tagline', 'description', 'phone', 'whatsapp', 'email',
            'website', 'address', 'google_maps_url', 'receipt_header',
            'receipt_footer', 'opening_time', 'closing_time',
        ];

        $merge = [];

        foreach ($nullableFields as $field) {
            if ($this->has($field) && $this->input($field) === '') {
                $merge[$field] = null;
            }
        }

        if ($merge !== []) {
            $this->merge($merge);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'google_maps_url' => ['nullable', 'url', 'max:255'],
            'currency' => ['sometimes', 'required', 'string', 'max:10'],
            'timezone' => ['sometimes', 'required', 'string', 'max:50'],
            'language' => ['sometimes', 'required', 'string', 'max:10'],
            'receipt_header' => ['nullable', 'string', 'max:255'],
            'receipt_footer' => ['nullable', 'string', 'max:255'],
            'opening_time' => ['nullable', 'date_format:H:i'],
            'closing_time' => ['nullable', 'date_format:H:i'],
            'logo' => ['nullable', 'file', 'image', 'max:2048'],
            'cover' => ['nullable', 'file', 'image', 'max:5120'],
            'favicon' => ['nullable', 'file', 'image', 'max:1024'],
        ];
    }
}
