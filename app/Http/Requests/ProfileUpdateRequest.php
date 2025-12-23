<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $languagesConfig = config('languages');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'hobbies' => ['nullable', 'string', 'max:500'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'], // 2MB max
            'location' => 'nullable|string|in:' . implode(',', array_keys(config('countries'))),
            'languages_teach' => ['nullable', 'array', 'max:8'],
            'languages_teach.*' => ['string', 'distinct', 'in:' . implode(',', array_keys($languagesConfig))],
            'languages_learn' => ['nullable', 'array', 'max:8'],
            'languages_learn.*' => ['string', 'distinct', 'in:' . implode(',', array_keys($languagesConfig))],
        ];
    }
}
