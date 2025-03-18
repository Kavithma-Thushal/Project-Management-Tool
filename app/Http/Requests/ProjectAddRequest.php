<?php

namespace App\Http\Requests;

use App\Classes\ErrorResponse;
use App\Enums\ProjectPrivacyTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ProjectAddRequest extends FormRequest
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

        $privacyTypes =  implode(',', ProjectPrivacyTypeEnum::values());
        return [
            'workspace_id' => 'required|exists:workspaces,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'privacy_type' => 'required|in:'.$privacyTypes,
            'skills.*.skill_id' => 'nullable|exists:skills,id',
            'task_statuses.*.name' => 'required',
        ];
    }

}
