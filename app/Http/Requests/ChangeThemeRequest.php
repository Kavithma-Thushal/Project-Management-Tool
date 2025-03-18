<?php

namespace App\Http\Requests;

use App\Classes\ErrorResponse;
use App\Enums\ThemeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ChangeThemeRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        ErrorResponse::validationError($validator);
    }

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'theme' => ['required', 'integer', 'in:' . ThemeEnum::LIGHT . ',' . ThemeEnum::DARK],
        ];
    }
}
