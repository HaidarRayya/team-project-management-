<?php

namespace App\Rules;

use App\Enums\EmployeeRole;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmployeeTaskRole implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $roles = implode(", ", array_column(EmployeeRole::cases(), 'value'));

        if (!($value == EmployeeRole::MANAGER->value
            ||  $value == EmployeeRole::DEVELOPER->value
            ||  $value == EmployeeRole::TESTER->value)) {
            $fail($roles . " حقل :attribute  يجب ان يكون احد القيم .");
        }
    }
}