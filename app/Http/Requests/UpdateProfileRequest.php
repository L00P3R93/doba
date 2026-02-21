<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
        $profile = $this->route('profile');

        return [
            'user_id' => ['sometimes', 'integer', 'exists:customers,id', Rule::unique('profiles', 'customer_id')->ignore($profile->id)],
            'bio' => ['nullable', 'string', 'max:1000'],
            'mpesa_phone' => ['nullable', 'string', 'regex:/^[0-9]{9,15}$/', Rule::unique('profiles', 'mpesa_phone')->ignore($profile->id)],
            'tier' => 'sometimes|string',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_id.exists' => 'The selected customer does not exist.',
            'customer_id.unique' => 'A profile already exists for this customer.',
            'bio.max' => 'The bio may not be greater than 1000 characters.',
            'mpesa_phone.regex' => 'The MPesa phone number must be between 9 and 15 digits.',
            'mpesa_phone.unique' => 'This MPesa phone number is already in use.',
        ];
    }
}
