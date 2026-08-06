<?php

namespace App\Http\Requests\Advertising;

use App\Enums\AdTargetLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAudioAdRequest extends FormRequest
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
            'headline' => ['required', 'string', 'max:255'],
            'cta_text' => ['required', 'string', 'max:50'],
            'cta_url' => ['nullable', 'url'],
            'target_level' => ['required', Rule::enum(AdTargetLevel::class)],
            'target_county_id' => ['required_if:target_level,county,subcounty,ward', 'nullable', 'exists:counties,id'],
            'target_sub_county_id' => ['required_if:target_level,subcounty,ward', 'nullable', 'exists:sub_counties,id'],
            'target_ward_id' => ['required_if:target_level,ward', 'nullable', 'exists:wards,id'],
            'base_price_per_impression' => ['required', 'numeric', 'in:0.20,0.30,0.40'],
            'budget' => ['required', 'numeric', 'min:50'],
            'audioFile' => ['required', 'mimes:mp3,ogg,wav', 'max:20480'], // 20MB max
            'bannerImage' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
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
            'audioFile.required' => 'Audio file is required.',
            'audioFile.mimes' => 'Audio file must be MP3, OGG, or WAV format.',
            'audioFile.max' => 'Audio file size must not exceed 20MB.',
            'bannerImage.mimes' => 'Banner image must be JPG, JPEG, PNG, or WEBP format.',
            'bannerImage.max' => 'Banner image size must not exceed 4MB.',
        ];
    }
}