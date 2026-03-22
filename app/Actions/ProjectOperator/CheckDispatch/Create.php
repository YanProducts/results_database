<?php
// プロジェクトや町目が既存のものの登録
namespace App\Actions\ProjectOperator\CheckDispatch;

use App\Models\DistributionPlanImport;
use App\Models\ProjectImport;
use App\Support\CommonModelHelpers\AddressHelpers;
use App\Support\CommonModelHelpers\DistributionPlanHelpers;
use App\Support\CommonModelHelpers\DistributionRecordHelpers;
use App\Support\CommonModelHelpers\ProjectHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Create{
    // 一時保存テーブルに格納(案件)
    public static function store_project_imports($project_name_and_towns,$same_projects_data,$place_id){

        foreach($project_name_and_towns as $project_name=>$each_sets){
            $import=new ProjectImport();

            // 誰からの登録か(これが残っている状態で次回のdispatchはできないようにする)
            $import->created_by=Auth::user()->id;

            // 期限
            $import->start_date=min(array_column($each_sets,"start_date"));
            $import->end_date=max(array_column($each_sets,"end_date"));

            // プロジェクト名
            $import->project_name=$project_name;

            // データ重複可能性が存在していればidを返す
            // 同じ案件名で複数地図があったとしても更新するものとしないものが混ざっていないと仮定
            if(in_array($project_name,array_column($same_projects_data,"nameForUI"))){
                // 同案件か不明の場合の、同案件とした場合のid
                $import->project_id=ProjectHelpers::get_latest_project_id_from_name($project_name);
            }

            // 最新の同案件ナンバー(1ヶ月以内のものも含むため最新のものを保存)
            $import->another_project_flag=ProjectHelpers::get_latest_another_project_flag($project_name);

            $import->save();
        }
    }

    // 一時保存テーブルに格納(案件)
    public static function store_plan_imports($project_name_and_towns,$same_town_data,$place_id){
        foreach($project_name_and_towns as $project_name=>$each_sets){
            foreach($each_sets as $each_set){

                // 住所のid(２度以上使うので先に保存)
                $address_id=AddressHelpers::get_id_from_city_and_town($each_set["city"],$each_set["town"]);

                $import=new DistributionPlanImport();

                // 誰からの登録か(これが残っている状態で次回のdispatchはできないようにする)
                $import->created_by=Auth::user()->id;

                $import->project_name=$project_name;


                // 案件id(同名のものは最新のもの)
                // 全て新しい案件でも、この項目は必要。なぜなら「同じ案件なので問答無用で更新」版で「同じ町目」のことが存在するから
                // 重複可能性の配列にプロジェクト名が含まれているもののみ選択

                if(in_array($project_name,array_column($same_town_data,"projectName"))){
                    // プロジェクトのid(２度以上使うので格納)
                    $project_id=ProjectHelpers::get_latest_project_id_from_name($project_name);

                    $import->project_id=$project_id;
                    $import->distribution_plan_exists=DistributionPlanHelpers::data_is_exists($project_id,$address_id);
                    $import->distribution_record_exists=DistributionRecordHelpers::data_is_exists($project_id,$address_id);
;                }
                // 営業所
                $import->place_id=$place_id;
                // 開始日
                $import->start_date=$each_set["start_date"];
                // 終了日
                $import->end_date=$each_set["end_date"];
                // 住所
                $import->address_id=$address_id;
                $import->save();
            }
        }
    }
}
