<?php

namespace App\Rules\BranchManager;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\DistributionPlan;

class PlanIdIsExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    // 計画されたplanのidが存在するか？
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach($value as $each_plan_id){
            if(is_null(DistributionPlan::find($each_plan_id))){
                $fail("計画に存在しない町目が入ってます");
            }
        }
    }
}
