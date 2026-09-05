<?php

namespace App\Rules\Common;

use App\Models\DistributionAssignment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class AssignedToUserRule implements ValidationRule
{
    // 報告書のassignIdはユーザーに割り当てられているか
    public function __construct(public int $staff_id)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(DistributionAssignment::where("id",$value)->value("staff_id")!==$this->staff_id){
            $fail("担当外の町目が含まれます");
        }
    }
}
