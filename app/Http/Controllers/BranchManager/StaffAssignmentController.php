<?php

namespace App\Http\Controllers\BranchManager;

use App\Actions\BranchManager\Assgin\CheckAssign\Read;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Actions\BranchManager\Assgin\GetDataFlowBeforeAssign;
use App\Actions\BranchManager\Assgin\StoreAssign;
use App\Constants\Date as DateConstants;
use App\Http\Requests\BranchManager\AssignStaffRequest;
use App\Utils\DateHelper;

// スタッフへの案件割り当て系統を行う
class StaffAssignmentController extends Controller
{

    //案件割り当て画面へ
    public function assign_staff(){

        // 今から何日後のデータまで取得するか
        $start_offset=DateConstants::StartOffsetInStaffAssignMent;
        $end_offset=DateConstants::EndOffsetInStaffAssignMent;
        // その営業所に来ている案件と、（その日出席している）所属スタッフを返す(当日から5日先まで)
        [$projects_and_towns,$staffs]=GetDataFlowBeforeAssign::get_projects_and_staffs_in_branch($start_offset,$end_offset);

        // 日付と案件とスタッフ一覧が表示(取り急ぎ併配も含めた案件ごと、のちにメイン案件でまとめる)
        return Inertia::render("BranchManager/ProjectAssignment/AssignProjectToStaff",[
            "prefix"=>"branch_manager",
            "what"=>"営業所担当",
            "type"=>"スタッフ割り当て",
            "projectsAndTowns"=>$projects_and_towns,
            "dateSets"=>DateHelper::get_date_key_value_sets_for_view("",$start_offset,$end_offset),
            "staffs"=>$staffs
        ]);
    }
    //案件割り当て投稿
    public function assign_staff_post(AssignStaffRequest $request){
        // データの取得(何度か使うため前もって展開)
        $all_data=$request->allData;
        $date=$request->assignDate;

        // plan_idが現時点で登録されているAssignやrecordと重複していた場合
        [$duplicated_in_sql,$duplicated_in_post]=Read::duplicated_data_check($all_data);

        // 重複があれば確認ページへ
        if(!empty($duplicated_in_sql ||!empty($duplicated_in_post))){

            // 重複ありのflash sessionを添えて返す
            return back()->with([
                "duplicated"=>[
                    "sql"=>$duplicated_in_sql,
                    "post"=>$duplicated_in_post
                ]
            ]);
            ;

        }

        // 重複がなければ投稿して確認ページへ
        StoreAssign::assign_staffs_to_plans($date,$all_data);

        return redirect()->route("");
    }

    // 重複確認でOKだった時(既にimportのsqlには入れている)
    public static function store_including_duplicated_plans(){

    }

}
