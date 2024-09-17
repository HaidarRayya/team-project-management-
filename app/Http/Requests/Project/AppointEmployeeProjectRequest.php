<?php

namespace App\Http\Requests\Project;

use App\Rules\EmployeeRole;
use App\Rules\EmployeeTaskRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AppointEmployeeProjectRequest extends FormRequest
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
            'role' => ['required', 'string', new EmployeeTaskRole, new EmployeeRole($this->route('user'))],
        ];
    }
    public function attributes()
    {
        return  [
            'role' => 'دور الموظف',
        ];
    }

    public function failedValidation($validator)
    {
        throw new HttpResponseException(response()->json(
            [
                'status' => 'error',
                'message' => "فشل التحقق يرجى التأكد من صحة القيم مدخلة",
                'errors' => $validator->errors()
            ],
            422
        ));
    }

    public function messages()
    {
        return  [
            'required' => 'حقل :attribute هو حقل اجباري ',
            'string' => 'حقل :attribute  يجب ان يكون نص ',
        ];
    }
}