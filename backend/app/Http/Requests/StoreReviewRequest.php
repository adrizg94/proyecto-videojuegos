<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rawg_id' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'release_date' => ['nullable', 'date'],
            'image' => ['nullable', 'string'],

            'rating' => ['required', 'integer', 'between:1,5'],
            'review' => ['required', 'string', 'max:2000'],

        ];
    }

    public function messages(): array
    {

        return [
            'rating.required' => 'The rating is required',
            'rating.integer' => 'The rating must be an integer',
            'rating.between' => 'The rating must be between 1 and 5',
            'review.required' => 'The review cannot be empty',
            'review.max' => 'The review cannot exceed 2000 characters',
        ];
    }
}
