<?php
// 営業所：期間内の日毎の提出済or提出予定の報告書
namespace App\Actions\BranchManager\Report;

use App\Support\Common\ModelHelpers\DistributionRecordHelpers;
use App\Support\Common\GetDateRangeQuery;
use App\Models\DistributionPlan;
use App\Support\Common\ModelHelpers\AddressHelpers;
use App\Support\Common\ModelHelpers\BranchManagerListHelpers;
use App\Support\Common\ModelHelpers\DistributionAssignmentHelpers;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use App\Support\Common\ModelHelpers\ProjectHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetOverviewByDay{

    // 複数スタッフ/日程の範囲に割り当てられた全データの概要
    //スタッフid=>[スタッフ名とデータ=>日付=>dateInViewとdata=>[all_main_project_names,]で返す
    public static function get_overview_data($staff_ids,$date_sets){

        // どこからアクセスしているか
        $role=Auth::user()->role;
        if($role=="field_staff"){
            // 単数を配列に揃える
            $staff_ids=[$staff_ids];
        }

        // sqlからデータを先に取得(N+1を防ぐため) //現場用のものとはstaff_idではなくstaff_ids
        [$submitted_data_in_the_staffs_and_dates,$assigned_data_in_the_staffs_and_dates, $main_plans_in_the_dates_and_the_place,$project_name_sets_key_by_project_id,$city_name_sets_key_by_address_id,$staff_id_name_sets]=self::get_data_in_sql($staff_ids,$date_sets,$role);

        // フォーマットを表示用に変換
        [$formatted_submitted_data_by_staffs_and_dates,$formatted_assigned_data_by_staffs_and_dates]=self::format_change_for_view_by_day($submitted_data_in_the_staffs_and_dates,$assigned_data_in_the_staffs_and_dates, $main_plans_in_the_dates_and_the_place,$project_name_sets_key_by_project_id,$city_name_sets_key_by_address_id,$staff_id_name_sets);

        // それぞれの日付における配列の取得
        // 配布済側にキーがある日付は未配布側にキーがない。配布済側にキーがない日付は未配布側にキーがあるかキーが存在しないか
       return $role=="branch_manager" ? collect($staff_id_name_sets)->mapWithKeys(fn($staff_name,$staff_id)=>[
            $staff_id=>[
                "staff_name"=>$staff_name,
                "each_data_by_staff"=> self::return_date_sets($date_sets,$formatted_submitted_data_by_staffs_and_dates,$formatted_assigned_data_by_staffs_and_dates,$staff_id)]])
       :self::return_date_sets($date_sets,$formatted_submitted_data_by_staffs_and_dates,$formatted_assigned_data_by_staffs_and_dates,$staff_ids[0]);
    }

    // N+1を防ぐために、データを先に取得
    public static function get_data_in_sql($staff_ids,$date_sets,$role){

        // 0:該当期間にその営業所に割り当てられた、planidがmain_id=nullのもの(つまりメイン案件)を取得
        $main_plans_in_the_dates_and_the_place=GetDateRangeQuery::get_date_range_query(DistributionPlan::select("project_id","address_id","main_id","id as plan_id")->where("place_id",$role=="branch_manager" ?BranchManagerListHelpers::get_login_user_place_id() : FieldStaffListHelpers::get_place_name_by_user($staff_ids[0]))->where("main_id",null),$date_sets,"start_date")->get();

        // 上記のid(他でいうplan_id)のリスト(recordとassignで2度使用)
        $main_plans_ids_in_the_dates_and_the_place=$main_plans_in_the_dates_and_the_place->pluck("plan_id");

        // 1：該当範囲、該当スタッフにおける配布済みデータの取得(メイン案件に限る)
        // planでのfilterはmain案件のみにするために必要
        $submitted_data_in_the_staffs_and_dates=DistributionRecordHelpers::data_in_the_range_and_staffs($date_sets,$staff_ids)->filter(fn($each_submitted_data)=>$main_plans_ids_in_the_dates_and_the_place->contains($each_submitted_data->plan_id));


        // ２：該当範囲を含むassignmentsのデータの取得:メイン案件に限る
        //１編集する際のこと２確認のため
        // sqlに入っている、そのスタッフの、期間内を含む案件を取得(n+1防止に一括取得)し、配布済みの日を除く
        // assignはmainIdは必ずnullなので、filterは不必要
        $assigned_data_in_the_staffs_and_dates=GetDateRangeQuery::get_date_range_query(DistributionAssignmentHelpers::get_not_submitted_data_from_plural_staff_ids($staff_ids,$submitted_data_in_the_staffs_and_dates),$date_sets,"date")->get();

        // 配布済みのフォーマット表示のために使うplanの案件Idと住所Idの一覧を先に取得(N+1対策)
        $submitted_distribution_plans= $main_plans_in_the_dates_and_the_place->whereIn("plan_id",$submitted_data_in_the_staffs_and_dates->pluck("plan_id"));
        // 未配布のplanの案件Idと住所Idの一覧を先に取得(N+1対策)
        $assigned_distribution_plans= $main_plans_in_the_dates_and_the_place->whereIn("plan_id",$assigned_data_in_the_staffs_and_dates->pluck("plan_id"));

        // 該当期間のplanに含まれる案件名一覧の取得(案件IDをキーにしている)
        $project_name_sets_key_by_project_id=ProjectHelpers::get_project_names_array_key_by_id([...$submitted_distribution_plans->pluck("project_id"),...$assigned_distribution_plans->pluck("project_id")]);

        // 該当期間のplanに含まれる市の名前一覧の取得
        $city_name_sets_key_by_address_id=AddressHelpers::get_only_city_name_from_ids([...$submitted_distribution_plans->pluck("address_id"),...$assigned_distribution_plans->pluck("address_id")]);

        // スタッフのid=>nameのリスト
        $staff_id_name_sets=FieldStaffListHelpers::get_staff_names_from_id_array($staff_ids);

        return[$submitted_data_in_the_staffs_and_dates,$assigned_data_in_the_staffs_and_dates, $main_plans_in_the_dates_and_the_place,$project_name_sets_key_by_project_id,$city_name_sets_key_by_address_id,$staff_id_name_sets];
    }

    // 得られたその日のデータを、表示用に変更(スタッフ&日毎に一覧を表示するだけなので、簡素なものでOK)
    // 全てのメイン案件名(sameProjectFlagもRoundNumberもいらない)、市名、配布数(配布済みのもののみ)
    //スタッフid=>[スタッフ名とデータ=>日付=>dateInViewとdata=>[all_main_project_names,]で返す
    public static function format_change_for_view_by_day($submitted_data_in_the_staffs_and_dates, $assigned_data_in_the_staffs_and_dates, $main_plans_in_the_dates_and_the_place,$project_name_sets_key_by_project_id,$city_name_sets_key_by_address_id,$staff_id_name_sets){

      // 配布済みの案件を日毎に分けた配列に変換し、表示用事の書式に合わせる //recordもassignも
        $formatted_submitted_data_by_staffs_and_dates=($submitted_data_in_the_staffs_and_dates->groupBy("staff_id")->mapWithKeys(fn($sumbitted_data_by_staff,$staff_id)=>[$staff_id=>["staff_name"=>$staff_id_name_sets[$staff_id],"dataSetsByStaff"=>$sumbitted_data_by_staff->groupBy("distribution_date")->mapWithKeys(fn($submitted_data_by_date,$each_date)=>
            // すでにgroupByされたdayごとのデータ
                [$each_date=>[
                    //project_idからproject_nameの一覧を取得し、address_idから市のリストを選択する
                    // ->only(キーのリスト)で、キーに該当するコレクションのみを残す
                    "all_main_project_names"=>$project_name_sets_key_by_project_id->only($submitted_data_by_date->pluck("project_id"))->implode("・"),
                    "all_city_lists"=>$city_name_sets_key_by_address_id->only($submitted_data_by_date->pluck("address_id"))->implode("・"),
                    // 全案件のメインをトータルした部数
                    "counts"=>$submitted_data_by_date->sum("distribution_count")
                ]]
        )]]));


        // 未配布の案件を表示用に直す
        $formatted_assigned_data_by_staffs_and_dates=($assigned_data_in_the_staffs_and_dates->groupBy("staff_id")->mapWithKeys(fn($assigned_data_by_staff,$staff_id)=>[$staff_id=>["staff_name"=>$staff_id_name_sets[$staff_id],"dataSetsByStaff"=>$assigned_data_by_staff->groupBy("date")->mapWithKeys(function($assigned_data_by_date,$each_date)use($main_plans_in_the_dates_and_the_place,$project_name_sets_key_by_project_id,$city_name_sets_key_by_address_id){
            // assignにはproject_idとaddress_idが含まれていないので、assignデータのplan_idをとってきて、そこに連動するproject_idとaddress_idをplanからとる必要がある
            // $main_plans_in_the_dates_and_the_placeのうち(plan)が、$assigned_data_by_date->pluck("plan_id")に含まれているものを取得し、そのproject_idとaddress_idを取得。
            $plans_in_the_day=$main_plans_in_the_dates_and_the_place->whereIn("plan_id",$assigned_data_by_date->pluck("plan_id"));

            return
            [$each_date=>[
                //planのproject_idからproject_nameの一覧を取得し、address_idから市のリストを選択する
                //onlyで、キーに該当するコレクションのみを残す
                "all_main_project_names"=>$project_name_sets_key_by_project_id->only($plans_in_the_day->pluck("project_id"))->implode("・"),
                "all_city_lists"=>($city_name_sets_key_by_address_id->only($plans_in_the_day->pluck("address_id"))->unique())->implode("・"),
                // 配布枚数は設定しない
                "counts"=>null
            ]];
         })
        ]]));

        return [$formatted_submitted_data_by_staffs_and_dates,$formatted_assigned_data_by_staffs_and_dates];
    }

    // コントローラーへ戻す部分のdate_setsより下位の部分
    public static function return_date_sets($date_sets,$formatted_submitted_data_by_staffs_and_dates,$formatted_assigned_data_by_staffs_and_dates,$staff_id,){
        return collect($date_sets)->mapWithKeys(fn($date_in_jpn,$each_date)=>[
                                    $each_date=>
                                       ($submitted_data_in_the_day=($formatted_submitted_data_by_staffs_and_dates[$staff_id]["dataSetsByStaff"][$each_date] ?? null)) ?
                                        [
                                            "status"=> "2",
                                            "data"=>$submitted_data_in_the_day,
                                            "dateInView"=>$date_in_jpn
                                        ]:(
                                        ($assigned_data_in_the_day=($formatted_assigned_data_by_staffs_and_dates[$staff_id]["dataSetsByStaff"][$each_date] ?? null)) ?

                                        [
                                            "status"=>1,
                                            "data"=>$assigned_data_in_the_day,
                                            "dateInView"=>$date_in_jpn
                                        ]:[
                                            "status"=>0,
                                            "data"=>null,
                                            "dateInView"=>$date_in_jpn
                                        ])
                  ]);

    }
}
