<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGameStatusRequest extends FormRequest
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

            'status' => [
            'required',
            'string',
            Rule::in([
                'backlog',
                'playing',
                'completed',
                'paused',
                'dropped',
            ]),
        ],
        ];
    }
}
