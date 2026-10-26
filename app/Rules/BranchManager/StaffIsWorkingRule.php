<?php

namespace App\Rules\BranchManager;

use App\Models\FieldStaffList;
use App\Support\Common\ModelHelpers\BranchManagerListHelpers;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
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

    //（最終的には）スタッフがその日に出勤しているかも一緒に返す!!!!!!
    // その場合はruleを新たに作成する!!!!(staffisworkingtodayあたり？)

        // そのスタッフがその営業所に所属していて、かつ稼働中か？
        if(!FieldStaffListHelpers::is_staff_is_active_and_in_the_place($value,BranchManagerListHelpers::get_login_user_place_id())){
            $fail("スタッフの営業所内での稼働が確認できません");
        }




    }
}
