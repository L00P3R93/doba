<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:customers',
            'phone' => 'required|string|unique:customers',
            'password' => 'required|string|min:8',
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
            'customer.name.required' => 'The name field is required.',
            'customer.name.max' => 'The name may not be greater than 255 characters.',
            'customer.email.required' => 'The email field is required.',
            'customer.email.max' => 'The email may not be greater than 255 characters.',
            'customer.email.unique' => 'The email has already been taken.',
            'customer.phone.required' => 'The phone field is required.',
            'customer.phone.unique' => 'The phone has already been taken.',
            'customer.password.required' => 'The password field is required.',
            'customer.password.min' => 'The password must be at least 8 characters.',
        ];
    }
}
