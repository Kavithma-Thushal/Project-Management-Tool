<?php

namespace App\Http\Requests;

use App\Models\Workspace;
use App\Classes\ErrorResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class WorkspaceDeleteRequest extends FormRequest
{
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

    public function withValidator($validator)
    {
        if ($validator->fails()) return;
        $validator->after(function ($validator) {
            if (!$this->isExists()) return $validator->errors()->add(config('common.generic_error'), 'Invalid id');
        });
    }

    private function isExists()
    {
        return Workspace::where('id', $this->id)->exists();
    }
}
