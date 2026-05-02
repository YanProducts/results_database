<?php

namespace App\Rules\BranchManager;

use App\Support\CommonModelHelpers\DistributionPlanHelpers;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class PlanValidateForDateRule implements ValidationRule
{

    // 宣言時に投稿されたdateを渡す
    public $date;
    public function __construct($date){
        $this->date=$date;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    // 当該planIdのstart_dateとend_dateの間に選択させたdateが入っているか
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        Log::info("koko");
        if(!DistributionPlanHelpers::is_plan_id_within_the_date($this->date,$value)){
            $fail("配布範囲外の町目が入ってます");
        }
    }
}
