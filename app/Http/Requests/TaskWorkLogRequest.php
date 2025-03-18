<?php

namespace App\Http\Requests;

use App\Classes\ErrorResponse;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class TaskWorkLogRequest extends FormRequest
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
//            'task_id' => 'required|exists:tasks,id',
            'task_id' => $this->isMethod('patch') ? 'nullable|exists:tasks,id' : 'required|exists:tasks,id',
            'description' => 'nullable|string|max:500',
            'date' => 'required|date',
            'spent_time' => 'required|numeric|min:0.1',
        ];
    }
}
