<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmployeeRole implements ValidationRule
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $employees = User::notAdmin()->select('id')->get();
        $employees_id = [];
        foreach ($employees as $i) {
            array_push($employees_id, $i->id);
        }
        if (!in_array($this->user->id, $employees_id)) {
            $fail(" حقل :attribute خاطئ , تحقق من رقم الموظف");
        }
    }
}