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
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The period field is required.',
            'name.max' => 'The period may not be greater than 255 characters.',
            'price.required' => 'The price field is required.',
            'duration_days.required' => 'The downloads limit field is required.',
        ];
    }
}
