<?php

namespace App\Rules\Common;

use App\Models\DistributionAssignment;
use App\Support\Common\ModelHelpers\DistributionAssignmentHelpers;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class AssignedToUserRule implements ValidationRule
{
    // 報告書のassignIdはユーザーに割り当てられているか
    // 不正値は型がその通りとは限らない(不正値もapp.phpではなくバリデーションで返すために、最初は通してvalidateの最初で制御)
    public function __construct(public mixed $staff_id,public mixed $date)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // そもそもの不正な型のチェック(数値は文字列に変換して可能かも見てみる)
        //パターン増えてくれば共通化
        if (
            (!is_int($this->staff_id) &&
             !(is_string($this->staff_id) && ctype_digit($this->staff_id)))
            || !is_string($this->date)
        ) {
            $fail("スタッフまたは日付の数値が不正です");
            return;
        }

        if(!DistributionAssignmentHelpers::is_assign_id_by_the_staff_and_date_exists($value,$this->staff_id,$this->date)){
            $fail("その日の配布で担当外の町目が含まれます");
        }
    }
}
