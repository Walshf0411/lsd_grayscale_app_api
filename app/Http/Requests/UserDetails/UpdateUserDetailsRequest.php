<?php

namespace App\Http\Requests\UserDetails;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserDetailsRequest extends FormRequest
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
            "first_name" => ["nullable", "string"],
            "last_name" => ["nullable", "string"],
            "mobile_number" => ["nullable", "string"],
            "date_of_birth" => ["nullable", "date"],
            "gender" => ["nullable", "string"]
        ];
    }
}
