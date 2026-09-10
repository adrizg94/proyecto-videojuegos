<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreThreadRequest extends FormRequest
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
            'title' => [
                'required',
                'string',
                'max:150',
            ],

            'body' => [
                'required',
                'string',
                'max:5000',
            ],

            // Solo se envían si el thread pertenece a un juego
            'rawg_id' => [
                'nullable',
                'integer',
            ],

            'name' => [
                'required_with:rawg_id',
                'nullable',
                'string',
                'max:255',
            ],

            'release_date' => [
                'nullable',
                'date',
            ],

            'image' => [
                'nullable',
                'string',
            ],
        ];
    }
}
