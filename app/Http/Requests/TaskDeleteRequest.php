<?php

namespace App\Http\Requests;

use App\Classes\ErrorResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Task;

class TaskDeleteRequest extends FormRequest
{
    /**
     * Handle failed validation.
     */
    protected function failedValidation(Validator $validator)
    {
        return ErrorResponse::validationError($validator);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Add additional validation rules.
     */
    public function withValidator($validator)
    {
        if ($validator->fails()) return;

        $validator->after(function ($validator) {
            if (!$this->isExists()) {
                $validator->errors()->add(config('common.generic_error'), 'Invalid task ID');
            }
        });
    }

    /**
     * Check if the task exists.
     */
    private function isExists(): bool
    {
        return Task::where('id', $this->route('id'))->exists();
    }
}
