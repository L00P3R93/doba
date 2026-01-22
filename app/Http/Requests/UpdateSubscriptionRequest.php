<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionRequest extends FormRequest
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
            'period' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric',
            'downloads_limit' => 'sometimes|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'period.max' => 'The period may not be greater than 255 characters.',
        ];
    }
}
