<?php

namespace App\Actions\BranchManager\Assgin\Management;
use App\Support\Common\ModelHelpers\BranchManagerListHelpers;
use App\Support\Common\ModelHelpers\DistributionAssignmentHelpers;
use App\Support\Common\ModelHelpers\DistributionPlanHelpers;
// 割り当てた案件のリセット
class ResetAssign{

    // リセットのプロセス
    // sql更新は内部でtry~catchを回し、conttollerで捕捉している
    public static function reset_process($date){
        // その日、その営業所に来ている、予定案件のplanのidの取得
        $plan_ids=DistributionPlanHelpers::get_main_plan_ids_in_the_place_and_date(BranchManagerListHelpers::get_login_user_place_id(),$date);

        // 上記のplan_idを持つassignの削除(planを複数日に跨いでいく場合があるので、dateも必ず加えること)
        DistributionAssignmentHelpers::delete_assign_in_the_date_and_plan_ids($date,$plan_ids);

    }
}
