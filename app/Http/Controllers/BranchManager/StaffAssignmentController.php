<?php

namespace App\Http\Controllers\BranchManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Actions\BranchManager\Assgin\Flow as AssignFlow;

// スタッフへの案件割り当て系統を行う
class StaffAssignmentController extends Controller
{

    //案件割り当て画面へ
    public function assign_staff(){
        // その営業所に来ている案件と、（その日出席している）所属スタッフを返す(当日から5日先まで)
        [$main_projects_and_towns,$sub_project_and_towns,$staffs]=AssignFlow::get_projects_and_staffs_in_branch();

        // 日付と案件とスタッフ一覧が表示(取り急ぎ併配も含めた案件ごと、のちにメイン案件でまとめる)
        return Inertia::render("BranchManager/ProjectAssignment/AssignProjectToStaff",[
            "prefix"=>"branch_manager",
            "what"=>"営業所担当",
            "type"=>"スタッフ割り当て",
            "mainProjects"=>$main_projects_and_towns,
            "subProjects"=>$sub_project_and_towns,
            "staffs"=>$staffs
        ]);
    }
    //案件割り当て投稿
    public function assign_staff_post(){

    }
}
