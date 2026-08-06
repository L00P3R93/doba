<?php

namespace App\Http\Requests\Advertising;

use App\Enums\AdTargetLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVideoAdRequest extends FormRequest
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
            'advertiser' => ['required', 'string', 'max:100'],
            'headline' => ['required', 'string', 'max:150'],
            'cta_text' => ['required', 'string', 'max:50'],
            'target_level' => ['required', Rule::enum(AdTargetLevel::class)],
            'target_county_id' => ['required_if:target_level,county,subcounty,ward', 'nullable', 'exists:counties,id'],
            'target_sub_county_id' => ['required_if:target_level,subcounty,ward', 'nullable', 'exists:sub_counties,id'],
            'target_ward_id' => ['required_if:target_level,ward', 'nullable', 'exists:wards,id'],
            'base_price_per_impression' => ['required', 'numeric', 'in:0.20,0.30,0.40'],
            'budget' => ['required', 'numeric', 'min:50'],
            'videoFile' => ['required', 'mimes:mp4,avi,mov,webm', 'max:102400'], // 100MB max
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'videoFile.required' => 'Video file is required.',
            'videoFile.mimes' => 'Video file must be MP4, AVI, MOV, or WEBM format.',
            'videoFile.max' => 'Video file size must not exceed 100MB.',
        ];
    }
}