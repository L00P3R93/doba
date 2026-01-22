<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerSubscriptionRequest extends FormRequest
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
            'customer_id' => ['required', 'exists:customers,id'],
            'subscription_id' => ['required', 'exists:subscriptions,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:active,expired,cancelled'],
            'downloads_used' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required' => 'Customer is required',
            'customer_id.exists' => 'Customer ID does not exist.',
            'subscription_id.required' => 'Subscription is required',
            'subscription_id.exists' => 'Subscription ID does not exist.',
            'start_date.required' => 'Start date is required',
            'start_date.date' => 'Start date is not a valid date.',
            'end_date.required' => 'End date is required',
            'end_date.date' => 'End date is not a valid date.',
            'end_date.after' => 'End date should be greater than start date',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be active, expired or cancelled',
            'downloads_used.required' => 'Downloads used is required',
            'downloads_used.integer' => 'Downloads used must be an integer.',
            'downloads_used.min' => 'Downloads used must be greater than or equal to 0.',
        ];
    }
}
