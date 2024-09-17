<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreNoteRequest extends FormRequest
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
            'descripation' => ['sometimes', 'min:25', 'max:255', 'string'],
        ];
    }
    public function attributes()
    {
        return  [
            'descripation' => 'وصف الملاحظة',
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
            'min' =>  'حقل :attribute  يجب ان يكون على الاقل 25 محرف ',
            'max' =>  'حقل :attribute  يجب ان يكون على الاكثر 255 محرف ',
        ];
    }
}