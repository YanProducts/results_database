<?php

namespace App\Rules\Common;

use App\Models\DistributionAssignment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AssignExistsRule implements ValidationRule
{
    // 割り当てられてたIdかのバリデーション
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!DistributionAssignment::where("id",$value)->exists()){
            $fail("対象外のデータが存在します");
        }
    }
}
