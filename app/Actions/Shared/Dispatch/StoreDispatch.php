<?php

namespace App\Actions\Shared\Dispatch;

use App\Exceptions\BusinessException;
use App\Models\DistributionPlan;
use App\Models\DistributionPlanImport;
use App\Models\Project;
use App\Support\Common\ModelHelpers\AddressHelpers;
use App\Support\Common\ModelHelpers\ProjectHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ProjectImport;
use App\Models\ProjectPlannedCount;
use App\Models\ProjectPlannedCountImport;
use App\Support\Common\ModelHelpers\ProjectPlannedCountHelpers;
use App\Support\ProjectOperator\DispatchHelpers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

// 営業所ごとに登録する
class StoreDispatch{


    // 重ならないと決まった時のprojectとDistributionPlanのsql挿入(日付と名前)
    public static function store_projects_data($project_name_and_towns,$place_id){
        DB::transaction(function()use($project_name_and_towns,$place_id){

            // projectsテーブルに挿入
            self::upsert_projects_table($project_name_and_towns);

            // project_name_and_townsは[テーマ名]=> ["main"=>["project_names"=>"","date_town_sets"=>"","sub"=>["ptojrct_name"と"date_town_sets"がいくつかの配列]]のデータ取得
            foreach($project_name_and_towns as $each_project){
                // 配布予定テーブルに挿入(プロジェクトIdを取得する必要があるため、前項と別々に行う)

                // メイン案件
                $main_sets=$each_project["main"];
                // メイン案件の案件Id
                $main_project_id=ProjectHelpers::get_latest_project_id_from_name($main_sets["project_name"]);

                // このメイン案件、この営業所で、このメイン案件が何番目かの取得(すなわちround_number)、ない場合はnullで変換(0とは明確に区別)
                // 併配案件でしかいっていない場合はnullで返る
                // これはProjectPlanContから取得するのが良い
                $max_round_number=ProjectPlannedCountHelpers::get_max_round_number_in_the_project_and_place($main_project_id,$place_id);

                // 併配案件ではnullで渡すから分岐は必要ない
                $round_number=$max_round_number === null ? 0 : $max_round_number+1;
                // Log::info($round_number); //1

                // 最も早い開始日と最も遅い終了日の取得(subはmainに連動する)
                [$earliest_start_date,$latest_end_date]=[min(array_column($main_sets["date_town_sets"],"start_date")),max(array_column($main_sets["date_town_sets"],"end_date"))];

                // メインの町丁目セット
                self::insert_distribution_plans_table($main_sets["date_town_sets"],$main_project_id,$place_id,true,null,$round_number);

                // メインの設定合計(最後に挿入されたidを返す)
                $project_planned_count_main_id=self::insert_total_data($main_project_id,$main_sets["total_count"],$place_id,$earliest_start_date,$latest_end_date,false,null,$round_number);

                // サブ案件
                $sub_sets=$each_project["sub"];
                foreach($sub_sets as $each_sets){
                    // プロジェクトのid(必然的に最も新しいプロジェクトidが取得される)
                    $sub_project_id=ProjectHelpers::get_latest_project_id_from_name($each_sets["project_name"]);
                    // 町丁目ごとの配布テーブル
                    self::insert_distribution_plans_table($each_sets["date_town_sets"],$sub_project_id,$place_id,false,$main_project_id,null);
                    // 設定合計
                    self::insert_total_data($sub_project_id,$each_sets["total_count"],$place_id,$earliest_start_date,$latest_end_date,false,$project_planned_count_main_id,null);
                }
            }
        });

    }

    // projectsテーブルに挿入(全て確認不要or確認し終えたデータ)
    public static function upsert_projects_table($project_name_and_towns){

        foreach($project_name_and_towns as $each_project){
            // project_name_and_townsは[テーマ名]=> ["main"=>["project_names"=>"","date_town_sets"=>"","sub"=>["ptojrct_name"と"date_town_sets"がいくつかの配列]]のデータ取得
            // project_name=>date_town_setsの配列に変更

            $main_project_array=[$each_project["main"]["project_name"]=>$each_project["main"]["date_town_sets"]];

            $sub_project_array=array_combine(array_column($each_project["sub"],"project_name"),array_column($each_project["sub"],"date_town_sets"));

            // アップザート
            self::project_upsert_query([...$main_project_array,...$sub_project_array]);
        }

    }

    // 実際のアップザートのクエリ
    public static function project_upsert_query($project_sets){


// Log::info($project_sets);
//   'LLL-10' =>
//   array (
//     0 =>
//     array (
//       'start_date' => '2026-10-30',
//       'end_date' => '2026-11-03',
//     ),
//   ),

        // upsert(複数データの変換可能)の基本形。作成するならこの情報
        // $upsert_array=Arr::map($project_sets,function($date_town_sets,$project_name){

        // Log::info(DispatchHelpers::get_earliest_start_date($project_name,$date_town_sets));
        // Log::info(DispatchHelpers::get_lateest_end_date($project_name,$date_town_sets));


        //      return[
        //         "project_name" => $project_name,
        //         "another_project_flag" => ProjectHelpers::get_latest_another_project_flag($project_name),//存在しないものは-0で返る(新規作成ではゼロ) //ここでは変更しない
        //         // 登録者は全体のAuthテーブルで取得しているので、案件担当でも営業所担当でも分ける必要はない
        //         "created_by"=>Auth::user()->id,
        //         // 投稿されたファイルと既存データの早い方をstart_dateに
        //         "start_date"=>DispatchHelpers::get_earliest_start_date($project_name,$date_town_sets),
        //         // 投稿されたファイルと既存データの遅い方をend_dateに
        //         "end_date"=>DispatchHelpers::get_lateest_end_date($project_name,$date_town_sets),
        // ];
        // });


        // dd("終了");


        $upsert_array=Arr::map($project_sets,fn($date_town_sets,$project_name)=>[
                "project_name" => $project_name,
                "another_project_flag" => ProjectHelpers::get_latest_another_project_flag($project_name),//存在しないものは-0で返る(新規作成ではゼロ) //ここでは変更しない
                // 登録者は全体のAuthテーブルで取得しているので、案件担当でも営業所担当でも分ける必要はない
                "created_by"=>Auth::user()->id,
                // 投稿されたファイルと既存データの早い方をstart_dateに
                "start_date"=>DispatchHelpers::get_earliest_start_date($project_name,$date_town_sets),
                // 投稿されたファイルと既存データの遅い方をend_dateに
                "end_date"=>DispatchHelpers::get_lateest_end_date($project_name,$date_town_sets),
        ]);

        // すでに重複データの除外は終了

         //対象となるキー。ここで同じものがあればupdate
          Project::upsert(
            // 基本変換リスト
            $upsert_array,
            // もし同じプロジェクト名で、同案件フラグナンバーが最大のものが存在した場合(1か月内なのでアップデート)
            ["project_name","another_project_flag"],
            // 重なる場合はstart_date(投稿データと現在SQLの早い方)とend_date(投稿データと現在SQLの遅い方)のみアップデート
            ["start_date","end_date"],
            );
    }


    // projectsテーブルを確認した値に応じてアップザート(自動更新期限切れかつ名前が重なる案件が存在）
    public static function upsert_after_confirmation_to_projects($new_projects){

        $user_id=Auth::user()->id;

        // ログインユーザーによって候補に挿入されているデータ
        $project_imports=ProjectImport::where("created_by",$user_id)->get();

        // 新案件で渡されてきたリストのプロジェクト
        $new_projects_lists=$project_imports->filter(fn($import)=>in_array($import->project_id,$new_projects));
        // 新案件のデータ挿入
        // 既存のプロジェクトとは違う新案件だと渡されてきたものは、同案件ナンバーを1つ足して、Projectテーブルに保存
        self::add_another_project_flag($new_projects_lists,$user_id);

        // 新案件では「ない」もののデータ変換
        // upsertに渡す配列(現在データにないものは新規作成、あるものは自動更新でend_dateを変更)
        $upsert_imports_array=DispatchHelpers::change_after_confirm_post_data_for_upsert($project_imports,$new_projects);

        // 新案件ではない案件のアップザート
        self::project_upsert_query($upsert_imports_array);
    }


    // 既存のプロジェクトとは違う新案件だと渡されてきたものは、同案件ナンバーを1つ足して、Projectテーブルに保存
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
    public static function insert_distribution_plans_table($date_town_sets,$project_id,$place_id,$is_main,$main_project_id,$round_number){


    // 現在、round_numberは「新案件だった場合」を踏まえてImport仮に入れている(新案件でその案件名をもつものが2つ以上あったとき、それらを区分けできていないため→未着手！！！！)

    // project_name_and_townsは[テーマ名]=> ["main"=>["project_names"=>"","date_town_sets"=>"","sub"=>["ptojrct_name"と"date_town_sets"がいくつかの配列]]のデータ取得

            foreach($date_town_sets as $each_sets){

                // 住所検索
                $address_id=AddressHelpers::get_id_from_city_and_town($each_sets["city"],$each_sets["town"]);

                $distribution_plans=new DistributionPlan();
                // 誰が登録したか
                $distribution_plans->created_by=Auth::user()->id;
                //プロジェクトのId
                $distribution_plans->project_id=$project_id;
                // 営業所Id
                $distribution_plans->place_id=$place_id;
                // このidの案件を、この営業所で行くのは何度目か
                $distribution_plans->round_number=$round_number;
                // 期限
                $distribution_plans->start_date=$each_sets["start_date"];
                $distribution_plans->end_date=$each_sets["end_date"];
                // 住所
                $distribution_plans->address_id=$address_id;
                // 備考(案件担当から)
                $distribution_plans->remark_from_operator="";
                // mapのナンバー
                $distribution_plans->map_number=$each_sets["map_number"];

                // メイン案件のid(メインとは分けて行っているため、必ずメイン案件は保存されている)
                if(!$is_main){
                    $distribution_plans->main_id=DistributionPlan::where([
                        // このメインプロジェクト、住所、営業所でmain_idがnullのものがメイン案件
                        // round_numberは案件Idと町目が同じなら営業所の中では必ず1つ(same_project_flagが同じ場合はあるが、大きな差はない)

                        ["project_id","=",$main_project_id],
                        ["main_id","=",null],
                        ["place_id","=",$place_id],
                        ["address_id","=",$address_id],
                        ])->orderByDesc("id")->value("id");
                }

                $distribution_plans->save();
            }

    }


    // 町目の重複確認をしたあとで、大丈夫だったき
    public static function upsert_after_confirmation_to_plans($new_projects){
        // このユーザーによって保存されたImport(必ずこの試行のみになる)
        $import_data=DistributionPlanImport::where("created_by",Auth::user()->id)->get();

        foreach($import_data as $each_import){
            // 複数回使うもの
            $address_id=$each_import->address_id;
            $place_id=$each_import->place_id;

            $plan=new DistributionPlan();
            // 該当重複しているデータに関わらず変わらない部分
            $plan->place_id=$place_id;
            $plan->start_date=$each_import->start_date;
            $plan->end_date=$each_import->end_date;
            $plan->address_id=$address_id;
            $plan->created_by=$each_import->created_by;
            $plan->map_number=$each_import->map_number;

            //セットになっているメイン案件の同町目のセットが保存されるidを返す。(メイン案件の場合はnull)
            // projectは重複に関わらず既に更新済。
            // main_idはimportではなくdistribution_plansのmain_id。そのため、「1：importから登録されたmain_idの案件名を取得 2：その案件の最大のanother_project_flag(つまり)のproject_idを取得 3：そのimportのtown」この3つから、「planにおける最大のプロジェクトid：該当町目を求め、そのplan_idを返している」
            // この手法の課題１：「投稿ごとに新案件と週案件が重なる時(最大のproject_idが該当するとは限らない)」、課題２：先にmainが登録されていることが重要(順序的にmain_idはメインのidがなければ尊愛しないが)。１は１ヶ月間以内は必ず同案件&同じIDが使われることは、そもそも考えにくいので同じとみなす。２はそもそも先でないと登録時にmain_idが見つからずにエラーになる
            $main_id=DistributionPlanImport::where("id",$main_id_from_import=$each_import->main_id)->value("project_name");
            if($main_id!=null){
                $plan->main_id=DistributionPlan::where("project_id",ProjectHelpers::get_latest_project_id_from_name($main_id))->where("address_id",$address_id)->where("place_id",$place_id)->orderByDesc("id")->value("id") ?? throw new BusinessException("予期せぬエラーです");
            }

            // 備考 //未着手
            $plan->remark_from_operator="";

            // 初投稿データのとき(すでにplan側にtransaction内部で保存されている)
            // project_idを更新しないとき＝これまでと重複の場合
            if($each_import->project_id==null || !in_array($each_import->project_id,$new_projects)){

                // プロジェクトは変更なしの時はプロジェクトのidをそのまま挿入
                // これまでと重複ではなく全く新しい案件の場合は新たに作られたidを取得（既に作成済）
                $new_project_id=$each_import->project_id ?? ProjectHelpers::get_latest_project_id_from_name($each_import->project_name);

            }else{
                // 新しい案件に更新するとき(トランザクション内部でも更新が反映しているためproject_idは変わっている)
                // projectsテーブルのprojectIdにおける最新の同案件ナンバーのidを取得(Projectは更新を終えている)
                if(empty($new_project_id= ProjectHelpers::get_latest_project_id_from_name(ProjectHelpers::get_project_name_from_id($each_import->project_id)))){ //これ更新に関わらず上の条件と同じでは？
                    throw new BusinessException("予期せぬエラーが発生しました\n最初からやり直してください");
                };
            }

            $plan->project_id=$new_project_id;

            // projectとplaceが同じものは「その営業所で何回目か」というカウントでround_numberを取得し、現在のものがnull(=存在しない)ではなくmain_idがnullのもの(併配ではない)は足して保存
            $max_round_number=
            ProjectPlannedCountHelpers::get_max_round_number_in_the_project_and_place($new_project_id,$place_id);

            // 併配案件ではnullで渡すから分岐は必要ない
            $round_number=$main_id_from_import!=null ? null : ($max_round_number === null ? 0 : $max_round_number+1);
            $plan->round_number=$round_number;

            // projectと住所が同じものの同じ町目を検索し、同じ案件としてsame_project_flagの最大値を取得。
            $max_same_project_flag = DistributionPlan::where("project_id", $plan->project_id)->where("address_id", $address_id)->max("same_project_flag");
            // まだ登録されていない案件は何もしない、登録済の案件は同案件No.を1つたす
            if($max_same_project_flag !== null){
                // 同町目ナンバーを記載
                $plan->same_project_flag=$max_same_project_flag+1;
            }


            $plan->save();
        }
    }

    // 合計部数の挿入(トランザクション内部には既にいる)
    public static function insert_total_data($project_id,$total_count,$place_id,$earliest_start_date,$latest_end_date,$need_main_id,$main_project_id,$round_number){
        $project_planned_count=new ProjectPlannedCount();
        $project_planned_count->project_id=$project_id; //案件Id
        $project_planned_count->place_id=$place_id; //営業所Id
        $project_planned_count->round_number=$round_number;// 同じ営業所、同じメイン案件が、これで何回目か(サブ案件の時はnullで渡ってる)
        $project_planned_count->start_date=$earliest_start_date; // 開始日
        $project_planned_count->end_date=$latest_end_date; // 終了日
        $project_planned_count->counts=$total_count; //合計部数
        if(!$need_main_id){
            $project_planned_count->main_id=$main_project_id;//対応するメイン案件(メイン案件のときはnull)
        }

        $project_planned_count->save();
        return $project_planned_count->id;
    }

    // 合計テーブルに記入
    public static function update_total_data_from_import(){
        // 一時保存合計テーブルにおけるユーザーのものの取得
        $imports=ProjectPlannedCountImport::where("created_by",Auth::user()->id)->get();
         // main_id検索用
         $id_by_imports_id=[];
        // n+1になるが、随時round_numberを更新する必要があるため、1つずつ行う
        foreach($imports as $import){
            $project_id=ProjectHelpers::get_latest_project_id_from_name($import->project_name);//案件id(新案件の場合は既に更新済)
            $place_id=$import->place_id;//営業所id
            $max_round_number=ProjectPlannedCount::where("project_id",$project_id)->where("place_id",$place_id)->max("round_number"); //最新のround_number

            $planned_count_data=new ProjectPlannedCount();
            $planned_count_data->place_id=$place_id; //営業所
            $planned_count_data->start_date=$import->start_date; //開始日
            $planned_count_data->end_date=$import->end_date; //終了日
            $planned_count_data->project_id=$project_id;//案件id(最新のもの)
            $planned_count_data->counts=$import->counts;//合計
            !empty($import->main_id) && $planned_count_data->main_id= $id_by_imports_id[$import->main_id];//メイン案件id(nullの場合もあり)

            $planned_count_data->round_number=!empty(($import->main_id)) ? null :($max_round_number===null ? 0 :$max_round_number + 1);//初期設定0なので、emptyではなくnullで捕捉

            $planned_count_data->save();
            // main_id検索用
            $id_by_imports_id[$import->id]=$planned_count_data->id;
        }
    }


}
