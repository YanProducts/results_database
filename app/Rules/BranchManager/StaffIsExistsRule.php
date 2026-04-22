<?php

namespace App\Rules\BranchManager;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\FieldStaffList;

class StaffIsExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // スタッフが存在しているかを返す
        if(!FieldStaffList::where("id",$value)->exists()){
            $fail("存在しないスタッフ番号が入力されています");
        }
    }
}
