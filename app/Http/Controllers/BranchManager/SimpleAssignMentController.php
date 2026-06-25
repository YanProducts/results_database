<?php

namespace App\Http\Controllers\BranchManager;

use App\Http\Controllers\Controller;
use App\Constants\Date as DateConstants;
use App\Actions\BranchManager\Assgin\GetDataFlowBeforeAssign;
use App\Http\Requests\BranchManager\AssignStaffRequest;
use Inertia\Inertia;

//営業所からスタッフ割り当ての際の簡易版(地図番号から行う)のコントローラー
class SimpleAssignMentController extends Controller
{
    // 案件割り当て
    //地図に少しでも入っていればその地図のmapを登録する
    public function assign_staff(){

        // 日付セット、その営業所に来ている案件と、日付と案件(町目)のインデックス、（その日出席している）所属スタッフを返す(当日から5日先まで)
        [$date_sets,$staffs,$plan_id_and_maps_by_main_projects,$date_projects_index]=GetDataFlowBeforeAssign::get_staffs_and_projects_in_branch_for_simple();

        // 日付と案件とスタッフ一覧が表示(取り急ぎ併配も含めた案件ごと、のちにメイン案件でまとめる)
        return Inertia::render("BranchManager/ProjectAssignment/SimpleAssignProjectToStaff",[
            "prefix"=>"branch_manager",
            "what"=>"営業所担当",
            "type"=>"スタッフ割り当て",
            "dateSets"=>$date_sets,
            "dateProjectsIndex"=>$date_projects_index,
            "staffs"=>$staffs,
            // 日付:[案件名:[地図番号:,planIds:]]
            "planIdsAndMapsByMainProjects"=>$plan_id_and_maps_by_main_projects
        ]);

    }

    //案件割り当て投稿
    // バリデーションは詳細版と同じものを使用(planIdsでどちらも投稿されているため)
    public function assign_staff_post(AssignStaffRequest $request){

    }

    // 重複確認
    public function store_including_duplicated_plans(){

    }



}
