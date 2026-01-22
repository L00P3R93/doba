<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'period' => 'required|string|max:255',
            'price' => 'required|numeric',
            'downloads_limit' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'period.required' => 'The period field is required.',
            'period.max' => 'The period may not be greater than 255 characters.',
            'price.required' => 'The price field is required.',
            'downloads_limit.required' => 'The downloads limit field is required.',
        ];
    }
}
