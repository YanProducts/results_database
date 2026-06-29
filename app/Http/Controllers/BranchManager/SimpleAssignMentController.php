<?php

namespace App\Http\Controllers\BranchManager;

use App\Http\Controllers\Controller;
use App\Actions\BranchManager\Assgin\GetDataFlowBeforeAssign;
use App\Http\Requests\BranchManager\AssignStaffRequest;
use App\Actions\BranchManager\Assgin\StoreAssign;
use App\Actions\BranchManager\Assgin\DuplicatedChek\Simple\Check as SimpleCheck;
use App\Actions\BranchManager\Assgin\DuplicatedChek\Simple\Insert;
use App\Models\DistributionAssignImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

//営業所からスタッフ割り当ての際の簡易版(地図番号から行う)のコントローラー
class SimpleAssignMentController extends Controller
{
    // 案件割り当て
    //地図に少しでも入っていればその地図のmapを登録する
    public function assign_staff(){

        // 前段階として、importを消しておく
        DistributionAssignImport::where("created_by",Auth::user()->id)->delete();

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
        // データの取得(何度か使うため前もって展開)
        $all_data=$request->allData;
        $date=$request->assignDate;

        //該当planIdを持ったMapを全て他の人が消化し切っていた場合=配布済エリア重複の可能性。一応戻して確認。
        $already_finisihed_maps=SimpleCheck::detail_check($all_data);

        if($already_finisihed_maps->isNotEmpty()){
            // 上記が存在する場合、importに投稿されたデータを全てそのまま登録
            Insert::insert_assign_import($date,$all_data);

            return back()->with([
                "finishedMap"=>[$already_finisihed_maps]
            ]);
        }

        //登録 //登録時に行うことはfrom_simple_flagをつけるだけで他は同じ
        StoreAssign::assign_staffs_to_plans($date,$all_data,true);

        return redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"branch_manager.handing_assignment","linkPageInJpn"=>"現在の割り当ての確認"]);
    }

    // 重複確認
    public function store_including_duplicated_plans(){
        // そのユーザーのデータをImportからAssign本番へ挿入（と削除）
        // StoreAssign::commit_duplicated_imports();

        return redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"","linkPageInJpn"=>"現在の割り当ての確認"]);
    }



}
