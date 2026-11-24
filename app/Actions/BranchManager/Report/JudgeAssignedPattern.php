<?php

namespace App\Actions\BranchManager\Report;

use App\Exceptions\BusinessException;
use App\Support\Common\ModelHelpers\DistributionAssignmentHelpers;
use App\Support\Common\ModelHelpers\DistributionProjectAssignmentHelpers;
use Illuminate\Support\Facades\Log;

// assignされたのが、通常盤かprojectOnly版かの判定
class JudgeAssignedPattern{
    public static function JudgeWhich($staff,$date){

        // そのスタッフ、日付のデータが通常盤のassignにあるか
        $assign_exists=DistributionAssignmentHelpers::data_is_exists_by_the_date_and_staff($staff,$date);

        // そのスタッフ、日付のデータがプロジェクト版のassignにあるか
        $project_only_assign_exists=DistributionProjectAssignmentHelpers::data_is_exists_by_the_date_and_staff($staff,$date);

        // 両方にない、もしくは両側に存在すればエラーを投げる(redirect処理されている過程なので、自動的にInertiaに限定しているapp.phpのexceptionにはいかない)
        if(!$assign_exists && !$project_only_assign_exists){
            throw new BusinessException("割り当てデータが登録されていません");
        }

        if($assign_exists && $project_only_assign_exists){
            throw new BusinessException("データが案件版と通常盤で重複されて登録されています");
        }

        // どちらかを返す
        return $assign_exists ? "normal" : "project_only";

    }
}
