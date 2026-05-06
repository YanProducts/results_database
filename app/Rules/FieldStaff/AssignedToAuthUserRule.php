<?php

namespace App\Rules\FieldStaff;

use App\Models\DistributionAssignImport;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class AssignedToAuthUserRule implements ValidationRule
{
    // 報告書のassignIdはユーザーに割り当てられているか
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(DistributionAssignImport::where("id",$value)->value("staff_id")!==Auth::user()->authable_id){
            $fail("担当外の町目が含まれます");
        }
    }
}
