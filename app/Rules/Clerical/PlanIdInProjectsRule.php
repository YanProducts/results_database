<?php

namespace App\Rules\Clerical;

use App\Support\Common\ModelHelpers\DistributionPlanHelpers;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

// そのplanIdは、別途パラメータで取得される案件Idを持つものか
class PlanIdInProjectsRule implements ValidationRule
{
    public function __construct(public string $project_id)
    {}
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //plan_idがproject_idを持っているか
        if(!DistributionPlanHelpers::check_project_id_by_plan_id($this->project_id,$value)){
            $fail("案件と配布データが一致しません");
        }
    }
}
