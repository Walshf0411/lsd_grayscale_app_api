<?php

namespace App\Http\Requests\ScreenConfig;

use Illuminate\Foundation\Http\FormRequest;

class GetScreenConfigsRequest extends FormRequest
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
            "user_id" => ["nullable"],
            "device_id" => ["required"],
            "screen_name" => ["required", "exists:screen_configs,screen_name"]
        ];
    }
}
