<?php
// 詳細番の重複チェック
// ひとまず現段階では「地図を全部行ききっている」という場合のみ表示する

namespace App\Actions\BranchManager\Assgin\DuplicatedChek\Simple;

use App\Models\DistributionAssignment;
use App\Models\DistributionPlan;
use App\Models\DistributionPlanImport;
use App\Models\DistributionRecord;
use Illuminate\Support\Facades\Log;

class Check{
    // 重複のチェック。
    public static function detail_check($all_data){

        // 提出された全planId
        $plan_ids=collect($all_data)->flatMap(fn($data)=>$data["planIds"]);

        // 配布済の報告書で、投稿されたplan_idを持つものをチェック
        $finished_plans=DistributionAssignment::whereIn("plan_id",$plan_ids)->where("status",true)->pluck("plan_id");

        if($finished_plans->isEmpty()){
            // 全部検索した場合と統一するためにcollect
            return collect();
        }

        // 投稿されたplan_idを持つmapを全て取得(シンプル版は「投稿されたplan_idにはないがmapには存在するid」は存在しない)
        // 表示の際に見ることができれば良いので、入れ子は細かくなくて良い
        $plans_group_by_maps=DistributionPlan::select("id","project_id","same_project_flag","round_number","map_number")->whereIn("id",$plan_ids)->get()->groupBy(fn($item)=>$item->project_id."_".$item->same_project_flag."_".$item->round_number."_".$item->map_number);

        // groupByされたplanIdが全てfinsihed_idに含まれている場合のものを抽出
        return $plans_group_by_maps->filter(function($plan_id_sets)use($finished_plans){
            ($plan_id_sets->pluck("id"))->every(fn($plan_id)=>$finished_plans->contains($plan_id));
        }
        );

    }
}
