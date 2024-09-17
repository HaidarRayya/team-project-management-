<?php

namespace App\Rules;

use App\Enums\TaskStatus as EnumsTaskStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TaskStatus implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $status = implode(", ", array_column(EnumsTaskStatus::cases(), 'value'));

        if (!($value == EnumsTaskStatus::PINDING->value
            ||  $value == EnumsTaskStatus::APPOINTED->value
            ||  $value == EnumsTaskStatus::STARTED->value
            ||  $value == EnumsTaskStatus::ENDED->value
            ||  $value == EnumsTaskStatus::FALIED->value)) {
            $fail($status . " حقل :attribute  يجب ان يكون احد القيم .");
        }
    }
}