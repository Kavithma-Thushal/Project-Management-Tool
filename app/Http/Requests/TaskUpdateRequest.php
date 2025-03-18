<?php

namespace App\Http\Requests;

use App\Classes\ErrorResponse;
use App\Enums\PriorityTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class TaskUpdateRequest extends FormRequest
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
        $priorities = implode(',', array_keys(PriorityTypeEnum::values()));
        return [
            'parent_task_id' => 'nullable|exists:tasks,id',
            'task_type_id' => 'required|exists:task_types,id',
            'assignee_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:' . $priorities,
            'estimated_hours' => 'nullable|numeric|min:0|not_in:0',
            'spent_hours' => 'nullable|numeric|min:0|not_in:0',
            'project_task_status_id' => 'required|exists:project_task_statuses,id',
            'media.*.media_id' => 'nullable|exists:media,id',

            // Work Log validation
            'work_log' => 'nullable|array',
            'work_log.description' => 'nullable|string|max:255',
            'work_log.date' => 'nullable|date',
            'work_log.spent_hours' => 'nullable|numeric|min:0',
        ];
    }
}
