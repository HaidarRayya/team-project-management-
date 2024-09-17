<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskStatus;
use App\Rules\DevelperRole;
use App\Rules\TaskPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreTaskRequest extends FormRequest
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
            'title' => ['required', 'min:3', 'max:255', 'string'],
            'description' => ['required', 'min:25', 'max:255', 'string'],
            'priority' =>    ['required', new TaskPriority],
            'due_date' => ['required', 'date_format:Y-m-d H:i:s'],
            'employee_id' => ['sometimes', 'integer', 'gt:0', new DevelperRole],
        ];
    }

    public function attributes()
    {
        return  [
            'title' => 'العنوان',
            'description' => 'الوصف',
            'priority' => 'الاولوية',
            'due_date' => 'تاريخ انتهاء المهمة',
            'employee_id' => 'رقم الموظف'

        ];
    }
    public function validatedWithCasts()
    {
        if ($this->employee_id == null) {
            return $this->merge([
                'manager_id' => Auth::user()->id,
            ]);
        } else {
            return  $this->merge([
                'employee_id' => $this->employee_id,
                'manager_id' => Auth::user()->id,
                'status' => TaskStatus::APPOINTED->value
            ]);
        }
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
            'title.min' => 'حقل :attribute يجب ان  يكون على الاقل 3 محرف',
            'description.min' => 'حقل :attribute يجب ان  يكون على الاقل 25 محرف',
            'max' => 'حقل :attribute يجب ان  يكون على الاكثر 10 محرف',
            'date_format' => 'حقل :attribute هو حقل تاريخ من الشكل سنة-شهر-يوم ساعة:دقيقة:ثانية',
            'integer' => 'حقل :attribute هو عدد صحيح   ',
            "gt" => "حقل :attribute يجب ان يكون اكبر  من الصفر ",
        ];
    }
}