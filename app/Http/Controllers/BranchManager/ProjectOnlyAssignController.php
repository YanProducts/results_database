<?php

namespace App\Http\Controllers\BranchManager;

use App\Actions\BranchManager\Assgin\GetDataFlowBeforeAssign;
use App\Http\Controllers\Controller;
use App\Models\DistributionAssignImport;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;

// 割り当てを案件のみから振り分ける
class ProjectOnlyAssignController extends Controller
{
    // 割り当て
    public static function assign_staff(){



        // 前段階として、importを消しておく
        //ここで案件版のimportも消すこと！！
        // この処理はマップと町目版でも同様にすること！！
        DistributionAssignImport::where("created_by",Auth::user()->id)->delete();




        // 日付セット、その営業所に来ている案件と、（その日出席している）所属スタッフを返す(当日から5日先まで)
        [$date_sets,$staffs,$date_projects_index,$assigned_days]=GetDataFlowBeforeAssign::get_staffs_and_projects_in_branch_for_project_only();

        // 日付と案件とスタッフ一覧が表示(取り急ぎ併配も含めた案件ごと、のちにメイン案件でまとめる)
        return Inertia::render("BranchManager/ProjectAssignment/ProjectOnlyAssignToStaff",[
            "prefix"=>"branch_manager",
            "what"=>"営業所担当",
            "type"=>"スタッフ割り当て",
            "dateSets"=>$date_sets,

            //日々の案件テーブルからとる(project_planned_counts) 案件名、roundNumber、place_idが同じ案件は一意に決まるので、このidを撮れば案件は一意に決まる
            //[日付=>[案件名(roundNumberつき),project_plannedのId(投稿用)]]
            "dateProjectsIndex"=>$date_projects_index,

            "staffs"=>$staffs,
            // 既に提出済み
            "assignedDays"=>$assigned_days
        ]);
    }

    // 投稿
    public static function assign_staff_post(){

    }
}
