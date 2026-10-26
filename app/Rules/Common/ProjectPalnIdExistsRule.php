<?php

namespace App\Rules\Common;

use App\Models\ProjectPlannedCount;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

// projectPlannedCountのidが存在するかどうかのrule
class ProjectPalnIdExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        if(!ProjectPlannedCount::find($value)){
            $fail("案件指定におけるエラーです");
        }
    }
}
