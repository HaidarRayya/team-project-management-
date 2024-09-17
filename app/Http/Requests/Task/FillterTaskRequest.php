<?php

namespace App\Http\Requests\Task;

use App\Rules\TaskPriority;
use App\Rules\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;

class FillterTaskRequest extends FormRequest
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
            'priority' => ['sometimes', new TaskPriority],
            'status' => ['sometimes', new TaskStatus]
        ];
    }
}
