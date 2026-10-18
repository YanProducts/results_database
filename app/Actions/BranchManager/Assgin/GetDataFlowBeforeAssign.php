<?php

// 営業所からスタッフ割り当ての流れ
namespace App\Actions\BranchManager\Assgin;

use App\Constants\Date;
use App\Models\BranchManagerList;
use App\Actions\BranchManager\Assgin\DataFetches\GetDataInPlaceAndPeriod;
use App\Actions\BranchManager\Assgin\DataFetches\GetDatePlanIndex;
use App\Actions\BranchManager\Assgin\DataFetches\GetStaffsInPlaceAndPeriod;
use App\Support\Common\ModelHelpers\DistributionAssignmentHelpers;
use App\Support\Common\ModelHelpers\ProjectPlannedCountHelpers;
use App\Utils\DateHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GetDataFlowBeforeAssign{

    // 簡易、詳細、案件のみ版のすべで必要なデータ

    // 市の名前と併配セットの列挙を返す!!(round_numberが複数ある時の説明用)

    public static function get_common_data(){
        // 今から何日後のデータまで取得するか
        $start_offset=Date::StartOffsetInStaffAssignMent;
        $end_offset=Date::EndOffsetInStaffAssignMent;

        // 現在ログインしている担当のの営業所のid取得
        $place_id=BranchManagerList::where("id",Auth::user()->authable_id)->value("place_id");

        // 日付セット
        $date_sets=DateHelper::get_date_key_value_sets_for_view("",$start_offset,$end_offset);

        // 指定曜日に出勤しているスタッフの取得
        // 日付=>[スタッフリスト(それぞれid=>staff_nameないときはユーザー名)]の順で取得
        //他でも使用する可能性があるので、Support層で定義
        $staffs_in_the_period=GetStaffsInPlaceAndPeriod::get_staffs_in_the_place_and_preriod($place_id,$date_sets);


        // 該当期間に計画されている案件を、[メイン案件(round_numberつき)]=>[each_sets=>[id(planの),address_name],sub_sets["prject_name"=>,id_sets=>[]]]の形で取得
        // planは複数日で渡ることが多いので、割り当て完了日のみの除去は困難(startからendまで全て終えている場合のみ除去は可能だが省略)
        // そのメイン案件がどのような町を含むものかと案件Idから判別するため、簡易版や案件のみ版でも必要
        $projects_and_towns=GetDataInPlaceAndPeriod::get_projects_and_towns_in_the_place_and_period($place_id,$start_offset,$end_offset);

        // 開始日から終了日までの中で、現在この営業所のassignのデータが1つでもある日をY-m-dの配列で取得（その日のデータは「データあり」で表示。データ軽量化のため後の取得データからも外す
        // 現状、end_dateは利用できてない
        $assigned_days=DistributionAssignmentHelpers::get_date_sets_having_assign_data($start_offset,$end_offset,$place_id);

        return [$start_offset,$end_offset,$place_id,$date_sets,$staffs_in_the_period,$projects_and_towns,$assigned_days];
    }

    // 詳細版
    // 日付、営業所に来ている案件、日付とキーのインデックス、現在のスタッフ取得
    public static function get_projects_and_staffs_in_branch(){

        [$start_offset,$end_offset,$place_id,$date_sets,$staffs_in_the_period,$projects_and_towns,$assigned_days]=self::get_common_data();

        // 日付とデータのインデックス
        //ここで割り当て済みのものを除く
        $date_projects_index=GetDatePlanIndex::get_date_projects_index($date_sets,$projects_and_towns,$assigned_days);

        return [$date_sets,$assigned_days,$projects_and_towns,$date_projects_index,$staffs_in_the_period];
    }

    // スタッフに割り当てる簡易版のデータを返す
    public static function get_staffs_and_projects_in_branch_for_simple(){

        // 共通データ
        [$start_offset,$end_offset,$place_id,$date_sets,$staffs_in_the_period,$projects_and_towns,$assigned_days]=self::get_common_data();


        // ここに案件説明のための住所取得のため、$project_and_townsの取得情報も入れる！！！

        // メイン案件ごとのplan_idとmapのセット/日付ごとにプロジェクトが格納されたもの
        [$plan_id_and_maps_by_main_projects,$date_projects_index]=GetDataInPlaceAndPeriod::get_project_data_for_simple_pattern($date_sets,$place_id,$start_offset,$end_offset,$assigned_days,$projects_and_towns);

        return [$date_sets,$staffs_in_the_period,$plan_id_and_maps_by_main_projects,$date_projects_index,$assigned_days];
    }

    // スタッフに割り当てる案件のみ版のデータを返す
    public static function get_staffs_and_projects_in_branch_for_project_only(){
            // 共通データ
            [$start_offset,$end_offset,$place_id,$date_sets,$staffs_in_the_period,$project_and_towns,$assigned_days]=self::get_common_data();

            // 期間内に営業所に来ている案件における[メイン案件名,roundNumbe,project_plannedのId,start_dare,end_date](project_planned_countより取得)
            $projects_sets=ProjectPlannedCountHelpers::get_id_and_project_names_for_view_in_date_array($start_offset,$end_offset,$place_id);

            // 並び替える[日付=>projectPlannedのid,表示用の案件名(roundNumber考慮),説明用セット(住所セット：説明用)]
            // projectPlannedCountのidを取得しておけば、その後にprojectIdもroundNumberも取得可能なので、この時点でセットする
            $date_projects_index=GetDatePlanIndex::get_project_data_for_project_only_pattern($date_sets,$projects_sets,$project_and_towns,$assigned_days);

            return [$date_sets,$staffs_in_the_period,$date_projects_index,$assigned_days];
    }

}
