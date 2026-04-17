<?php

namespace App\Rules\BranchManager;


use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StaffIsWorkingRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //（最終的には）スタッフがその日に出勤しているかを返す



    }
}
