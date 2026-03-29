<?php
// プロジェクトや町目が既存のものの登録
namespace App\Actions\ProjectOperator\CheckDispatch;

use App\Exceptions\BusinessException;
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
        // 誰からの登録か(これが残っている状態で次回のdispatchはできないようにする)
        $auth_id=Auth::user()->id;
        // project_name_and_townsは[テーマ名]=> ["main"=>["project_names"=>"","date_town_sets"=>"","sub"=>["ptojrct_name"と"date_town_sets"がいくつかの配列]]のデータ取得

        // それぞれの案件を見ていく
        foreach($project_name_and_towns as $each_project){
            $main_sets=$each_project["main"];
            $sub_sets=$each_project["sub"];

            // 重複がある場合のメイン案件の全体データの登録
            self::sql_procedure_in_project_imports($main_sets,$auth_id,$same_projects_data);

            // 重複がある場合のサブ案件の全体データの登録
            foreach($sub_sets as $each_sub){
                self::sql_procedure_in_project_imports($each_sub,$auth_id,$same_projects_data);
            }
        }
    }

    // 実際に重複がある場合のプロジェクトデータセーブ
    public static function sql_procedure_in_project_imports($sets,$auth_id,$same_projects_data){

            $project_name=$sets["project_name"];
            $date_town_sets=$sets["date_town_sets"];
            $import=new ProjectImport();
            // プロジェクト名
            $import->project_name=$project_name;
            // 誰からの登録か(これが残っている状態で次回のdispatchはできないようにする)
            $import->created_by=$auth_id;
            // 期限
            $import->start_date=min(array_column($date_town_sets,"start_date"));
            $import->end_date=max(array_column($date_town_sets,"end_date"));

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



    // 一時保存テーブルに格納(案件)
    public static function store_plan_imports($project_name_and_towns,$same_town_data,$place_id){
        $auth_id=Auth::user()->id;
        // project_name_and_townsは[テーマ名]=> ["main"=>["project_names"=>"","date_town_sets"=>"","sub"=>["ptojrct_name"と"date_town_sets"がいくつかの配列]]のデータ取得
    foreach($project_name_and_towns as $each_project){
        $main_sets=$each_project["main"];
        $sub_sets=$each_project["sub"];
        // メイン案件の名前
        $main_project_name=$main_sets["project_name"];

        // メイン案件の町目を入れていく
        foreach($main_sets["date_town_sets"] as $each_set){
                // メインデータセーブ
                self::sql_procedure_in_plan_imports(null,$main_project_name,$each_set,AddressHelpers::get_id_from_city_and_town($each_set["city"],$each_set["town"]),$place_id,$auth_id,$same_town_data,true);
        }

        foreach($sub_sets as $each_sub){
            foreach($each_sub["date_town_sets"] as $each_set){
                // 併配データセーブ
                self::sql_procedure_in_plan_imports($main_project_name,$each_sub["project_name"],$each_set,AddressHelpers::get_id_from_city_and_town($each_set["city"],$each_set["town"]),$place_id,$auth_id,$same_town_data,false);
            }
        }
        }
    }
    // 実際に重複がある場合の町目データセーブ
    public static function sql_procedure_in_plan_imports($main_project_name,$project_name,$each_set,$address_id,$place_id,$auth_id,$same_town_data,$is_main){
                $import=new DistributionPlanImport();

                // 誰からの登録か(これが残っている状態で次回のdispatchはできないようにする)
                $import->created_by=$auth_id;

                $import->project_name=$project_name;
                // round_countは本番のplanでのみ取得

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
                // マップナンバー
                $import->map_number=$each_set["map_number"];

                // メイン案件のid、一時保存データではimportsのメイン案件と町目が合うid
                // 本挿入データではメイン案件の町目まで挿入してsaveした後に、同じメインプロジェクト名同じ町目のものを取得する
                if(!$is_main){
                    // どのメイン案件と紐づくか(メイン案件はすでに必ず登録されている)
                    // 該当するものが複数ある場合、最も新しいidを取得
                    $import->main_id=DistributionPlanImport::where([
                        ["project_name","=",$main_project_name],
                        ["place_id","=",$place_id],
                        ["address_id","=",$address_id],
                        ])->orderBy("id","desc")->value("id") ?? throw new BusinessException("データ挿入でエラーが発生しました");
                }

                $import->save();
    }
}
