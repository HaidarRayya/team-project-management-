<?php

namespace App\Rules;

use App\Enums\TaskPriority as EnumsTaskPriority;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TaskPriority implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $roles = implode(", ", array_column(EnumsTaskPriority::cases(), 'value'));

        if (!($value == EnumsTaskPriority::NORMAL->value ||  $value == EnumsTaskPriority::MEDIUM->value ||  $value == EnumsTaskPriority::IMPORTANT->value)) {
            $fail($roles . " حقل :attribute  يجب ان يكون احد القيم .");
        }
    }
}
