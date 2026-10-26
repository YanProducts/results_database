<?php

namespace App\Http\Controllers\BranchManager;

use App\Actions\BranchManager\Assgin\DuplicatedChek\Common\Delete;
use App\Actions\BranchManager\Assgin\GetDataFlowBeforeAssign;
use App\Actions\BranchManager\Assgin\StoreAssign;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchManager\ProjectOnlyAssignRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;

// 割り当てを案件のみから振り分ける
class ProjectOnlyAssignController extends Controller
{
    // 割り当て
    public static function assign_staff(){

        // 前段階として、importを消しておく(既に前町目行っている差し戻し用)
        Delete::delete_imports_by_auth_user();

        // 日付セット、その営業所に来ている案件と、（その日出席している）所属スタッフを返す(当日から5日先まで)
        [$date_sets,$staffs,$date_projects_index,$assigned_days]=GetDataFlowBeforeAssign::get_staffs_and_projects_in_branch_for_project_only();

        // 日付と案件とスタッフ一覧が表示(取り急ぎ併配も含めた案件ごと、のちにメイン案件でまとめる)
        return Inertia::render("BranchManager/ProjectAssignment/ProjectOnlyAssignToStaff",[

            "prefix"=>"branch_manager",
            "what"=>"営業所担当",
            "type"=>"スタッフ割り当て",
            "dateSets"=>$date_sets,

            //日々の案件テーブルからとる(project_planned_counts) 案件名、roundNumber、place_idが同じ案件は一意に決まるので、このidを撮れば案件は一意に決まる
            //[日付=>[案件名(roundNumberつき),project_plannedのId(投稿用)、address_sets]]
            "dateProjectsIndex"=>$date_projects_index,

            "staffs"=>$staffs,
            // 既に提出済み
            "assignedDays"=>$assigned_days
        ]);
    }

    // 投稿
    public static function assign_staff_post(ProjectOnlyAssignRequest $request){

        // 差し戻すケース。あるか？
        // その案件の全部町目がrecordに入っていたとき？
        // その場合はimportsに入れる


        // 以下はsimple版のメモ
        //該当planIdを持ったMapを全て他の人が消化し切っていた場合=配布済エリア重複の可能性。一応戻して確認。
        // $already_finisihed_maps=SimpleCheck::detail_check($all_data);

        // if($already_finisihed_maps->isNotEmpty()){
        //     // 上記が存在する場合、importに投稿されたデータを全てそのまま登録
        //     Insert::insert_assign_import($date,$all_data);

        //     return back()->with([
        //         "finishedMap"=>[$already_finisihed_maps]
        //     ]);
        // }


        //登録(ここでのエラーはview_error捕捉。Inertiaが生じた中でのことなので、bootstrap/appで返る)
        StoreAssign::assign_project_to_staffs($request->assignDate,$request->allData);

        return redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"branch_manager.assign_overview","linkPageInJpn"=>"現在の割り当ての確認"]);

    }
}
