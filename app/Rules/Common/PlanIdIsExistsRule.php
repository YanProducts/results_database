<?php

namespace App\Rules\Common;

use App\Models\DistributionPlan;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PlanIdIsExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //plan_idが存在するか？（単数）
        if(is_null(DistributionPlan::find($value))){
           $fail("計画に存在しない町目が入ってます");
        }
    }
}
