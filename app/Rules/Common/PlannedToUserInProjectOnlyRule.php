<?php

namespace App\Rules\Common;

use App\Exceptions\BusinessException;
use App\Models\DistributionProjectAssignment;
use App\Support\Common\ModelHelpers\DistributionPlanHelpers;
use App\Support\Common\ModelHelpers\DistributionProjectAssignmentHelpers;
use App\Support\Common\ModelHelpers\ProjectPlannedCountHelpers;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

// 案件型でのassignに、そのスタッフ、その日付、そのplan_idが含まれるか(plan_idからproject_plan_idを取得し、最後にproject_assignmentsと照合)
class PlannedToUserInProjectOnlyRule implements ValidationRule
{

    // 不正値は型がその通りとは限らない(不正値もapp.phpではなくバリデーションで返すために、最初は通してvalidateの最初で制御)
    public function __construct(public mixed $staff_id,public mixed $date)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    // そのplan_id(distribution_plansの)が、スタッフが配布予定か否か
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

        // そのplan_idから、project_idとround_numberとplace_idを取得
        // すでにplan_idの存在は確認済(僅かな隙で大元のデータが削除されている場合は大元のエラールートへ)
        [$project_id,$round_number,$place_id]=DistributionPlanHelpers::get_project_and_round_number_and_place_id($value);

        // 上記で案件は一意に決定、そのproject_planned_countを取得
        $project_plan_id=ProjectPlannedCountHelpers::get_project_planned_id($project_id,$round_number,$place_id);

        // これ日付もいる？？？？
        // 該当スタッフが、該当のproject_planを、その日付で持っているか
        // つまり、selectedDateがこの範囲に入っているか

        // そのproject_plan_idは、Distribution ProjectAssignmentsでstaff_idに指定されたものの中に入っているか
        if(!DistributionProjectAssignmentHelpers::is_project_plan_id_by_the_staff_and_date_exists($this->staff_id,$this->date,$project_plan_id)){
            $fail("案件取得の際のエラーです");
        }
    }
}
