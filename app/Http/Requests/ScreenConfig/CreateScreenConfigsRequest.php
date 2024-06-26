<?php

namespace App\Http\Requests\ScreenConfig;

use Illuminate\Foundation\Http\FormRequest;

class CreateScreenConfigsRequest extends FormRequest
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
            "screen_name" => ["required", "unique:screen_configs,screen_name"],
            "sections" => ["required", "array"],
            "sections.*.name" => ["required"],
            "sections.*.type" => ["required"],
            "sections.*.attributes" => ["nullable", "json"],
        ];
    }
}
