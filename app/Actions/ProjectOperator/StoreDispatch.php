<?php

namespace App\Actions\ProjectOperator;

use App\Exceptions\BusinessException;
use App\Models\DistributionPlan;
use App\Models\DistributionPlanImport;
use App\Models\DistributionRecord;
use App\Models\Project;
use App\Support\CommonModelHelpers\AddressHelpers;
use App\Support\CommonModelHelpers\DistributionPlanHelpers;
use App\Support\CommonModelHelpers\ProjectHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ProjectImport;
use App\Support\ProjectOperator\DispatchHelpers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

// 営業所ごとに登録する
class StoreDispatch{


    // 重ならないと決まった時のprojectとDistributionPlanのsql挿入(日付と名前)
    // むしろProjectテーブルいらないかも！？
    // 今後にProjectの大元を管理することも含めておいておく
    public static function store_projects_data($project_name_and_towns,$place){
        // projectsテーブルに挿入(*projectsテーブル自体がなくなる可能性はあり)

        self::upsert_projects_table($project_name_and_towns);

         foreach($project_name_and_towns as $project_name=>$date_town_sets){

            // 配布予定テーブルに挿入(プロジェクトIdを取得する必要があるため、前項と別々に行う)
            self::insert_distribution_plans_table($date_town_sets,$project_name,$place);
        }

    }

    // projectsテーブルに挿入(全て確認不要or確認し終えたデータ)
    // projectsテーブル自体がなくなる可能性はあり
    public static function upsert_projects_table($project_name_and_towns){

        // upsert(複数データの変換可能)の基本形。作成するならこの情報
        $upsert_array=Arr::map($project_name_and_towns,fn($date_town_sets,$project_name)=>[
                "project_name" => $project_name,
                "another_project_flag" => ProjectHelpers::get_latest_another_project_flag($project_name),//存在しないものは-0で返る(新規作成ではゼロ) //ここでは変更しない
                "created_by"=>Auth::user()->id,
                // 投稿されたファイルと既存データの早い方をstart_dateに
                "start_date"=>DispatchHelpers::get_earliest_start_date($project_name,$date_town_sets),
                // 投稿されたファイルと既存データの遅い方をend_dateに
                "end_date"=>DispatchHelpers::get_lateest_end_date($project_name,$date_town_sets),
        ]);

        // すでに重複データの除外は終了
        DB::transaction(function()use($upsert_array){

         //対象となるキー。ここで同じものがあればupdate
          Project::upsert(
            // 基本変換リスト
            $upsert_array,
            // もし同じプロジェクト名で、同案件フラグナンバーが最大のものが存在した場合(1か月内なのでアップデート)
            ["project_name","another_project_flag"],
            // 重なる場合はstart_dateとend_dateのみアップデート
            ["start_date","end_date"],
            );
        });
    }


    // projectsテーブルを確認した値に応じてアップザート(自動更新期限切れかつ名前が重なる案件が存在）
    public static function upsert_after_confirmation_to_projects($new_projects){

        $user_id=Auth::user()->id;

       // ログインユーザーによって候補に挿入されているデータ
        $project_imports=ProjectImport::where("created_by",$user_id)->get();


        // 新案件で渡されてきたリストのプロジェクト
        $new_projects_lists=$project_imports->filter(fn($import)=>in_array($import->project_id,$new_projects));

        // 既存のプロジェクトとは違う新案件だと渡されてきたものは、同案件ナンバーを1つ足す
        self::add_another_project_flag($new_projects_lists,$user_id);

        // upsertに渡す配列(現在データにないものは新規作成、あるものは自動更新でend_dateを変更)
        $upsert_imports_array=DispatchHelpers::change_after_confirm_post_data_for_upsert($project_imports,$new_projects);

        // アップザート
        self::upsert_projects_table($upsert_imports_array);

    }


    // 既存のプロジェクトとは違う新案件だと渡されてきたものは、同案件ナンバーを1つ足す
    public static function add_another_project_flag($new_projects_lists,$user_id){
        foreach($new_projects_lists as $new_project){
            $project_name=$new_project->project_name;
            $project=new Project;
            $project->start_date=$new_project->start_date;
            $project->end_date=$new_project->end_date;
            $project->project_name=$project_name;
            $project->created_by=$user_id;
            $project->another_project_flag=ProjectHelpers::get_latest_another_project_flag($project_name)+1;
            $project->save();
        }
    }


    // 重ならないと決定したあとで、配布予定の案件や町目などを入れていく
    public static function insert_distribution_plans_table($date_town_sets,$project_name,$place_id){
        DB::transaction(function()use($project_name,$date_town_sets,$place_id){
            foreach($date_town_sets as $each_sets){
                $distribution_plans=new DistributionPlan();
                // 誰が登録したか
                $distribution_plans->created_by=Auth::user()->id;
                //プロジェクトのId
                $distribution_plans->project_id=ProjectHelpers::get_latest_project_id_from_name($project_name);
                // 営業所Id
                $distribution_plans->place_id=$place_id;
                // 期限
                $distribution_plans->start_date=$each_sets["start_date"];
                $distribution_plans->end_date=$each_sets["end_date"];
                // 住所
                $distribution_plans->address_id=AddressHelpers::get_id_from_city_and_town($each_sets["city"],$each_sets["town"]);
                // 備考(案件担当から)
                $distribution_plans->remark_from_operator="";
                $distribution_plans->save();
            }
        });
    }


    // 町目の重複確認をしたあとで、大丈夫だったき
    public static function upsert_after_confirmation_to_plans($new_projects){
        // このユーザーによって保存されたImport(必ずこの試行のみになる)
        $import_data=DistributionPlanImport::where("created_by",Auth::user()->id)->get();

        foreach($import_data as $each_import){
            $plan=new DistributionPlan();
            $plan->place_id=$each_import->place_id;
            $plan->start_date=$each_import->start_date;
            $plan->end_date=$each_import->end_date;
            $plan->address_id=$each_import->address_id;
            $plan->created_by=$each_import->created_by;
            $plan->remark_from_operator="";


            // project_idを更新しないとき＝これまでと重複の場合
            if(!in_array($each_import->project_id,$new_projects)){

                // プロジェクトは変更なしの時はプロジェクトのidをそのまま挿入
                // これまでと重複ではなく全く新しい案件の場合は新たに作られたidを取得
                $plan->project_id=$each_import->project_id ?? ProjectHelpers::get_latest_project_id_from_name($each_import->project_name);

                // importのditribution_plan_idもしくはdistribution_record_idが記入されているとき、つまり同じプロジェクトの同じ住所を、複数の営業所もしくは回数に分けているとき
                if(!empty($each_import->distribution_plan_exists) || !empty($each_import->distribution_record_exists)){
                    // same_project_flagを更新(recordのみに入っているものは、0にならずに1になる)
                    // そのプロジェクトと町丁目におけるrecordとplanの数を取得


                    // この部分、本来はN+1検索になっているので、データ増えた時に要注意!!!

                    // planに入っている個数
                    $plan_counts=DistributionPlan::where("project_id",$each_import->project_id)->where("address_id",$each_import->address_id)->count();
                    // recordに入っている個数(都度更新されるので0か1)
                    $record_counts=DistributionRecord::where("project_id",$each_import->project_id)->where("address_id",$each_import->address_id)->exists();
                    // 同町目ナンバーを記載
                    $plan->same_project_flag=$plan_counts+$record_counts;
                }
            }else{
                // 新しい案件に更新するとき(トランザクション内部でも更新が反映)
                // projectsテーブルのprojectIdにおける最新の同案件ナンバーのidを取得
                if(empty($new_project_id= ProjectHelpers::get_latest_project_id_from_name(ProjectHelpers::get_project_name_from_id($each_import->project_id)))){
                    throw new BusinessException("予期せぬエラーが発生しました\n最初からやり直してください");
                };

                $plan->project_id=$new_project_id;
            }

            $plan->save();
        }


    }


}
