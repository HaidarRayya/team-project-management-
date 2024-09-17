<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProjectRequest extends FormRequest
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
            'name' => ['sometimes', 'min:3', 'string'],
            'descripation' => ['sometimes', 'min:25', 'string'],
        ];
    }
    public function attributes()
    {
        return  [
            'name' => 'اسم المشروع',
            'descripation' => 'وصف المشروع',
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
            'string' => 'حقل :attribute  يجب ان يكون نص ',
            'name.min' =>  'حقل :attribute  يجب ان يكون على الاقل 3 محارف ',
            'descripation.min' =>  'حقل :attribute  يجب ان يكون على الاقل 25 محرف ',
        ];
    }
}