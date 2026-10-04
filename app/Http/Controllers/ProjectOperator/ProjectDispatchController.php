<?php

namespace App\Http\Controllers\ProjectOperator;

use App\Actions\Shared\Dispatch\ProjectDispatchProcedure;
use App\Actions\Shared\Dispatch\CheckDispatch\Delete as CheckDelete;
use App\Actions\Shared\Dispatch\CheckDispatch\Flow as CheckFlow;
use App\Http\Controllers\Controller;
use App\Support\Common\ModelHelpers\PlaceHelpers;
use App\Http\Requests\ProjectOperator\ConfirmRequest;
use App\Http\Requests\ProjectOperator\DispatchRequest;
use App\Support\ProjectOperator\DispatchHelpers;
use Inertia\Inertia;

class ProjectDispatchController extends Controller
{
    // 営業所(外注含む)へ振る案件を選択する画面を表示
    public function dispatch_project(){

        // 現在の確認ページ用のsessionと、そのユーザーが投稿した「確認中」のテーブルは消去する
        CheckDelete::automatic_delete_from_same_user();

        return Inertia::render("ProjectOperator/ProjectDispatch/SendProjectToBranch",[
            // 営業所リスト
            "placeSets"=>PlaceHelpers::get_active_places(),
            "type"=>"案件→営業所"
        ]);
    }

    // 営業所(外注含む)へ振る案件投稿→以前と同じものか確認
    public function dispatch_project_post(DispatchRequest $request){

        // 割り当てが成功か失敗か重複戻しか
        $dispatch_test_result=ProjectDispatchProcedure::project_dispatch_procedure($request->fileSets,"project_operator",$request->place,);

        return
        match($dispatch_test_result){
            "view_error"=>redirect()->route("view_error"),
            "duplicated"=> redirect()->route("project_operator.confirm_dispatch"),

            "success"=> redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"project_operator.project_overview","linkPageInJpn"=>"確認ページ"])
        };
    }

    // 重複可能性がある案件をどうするかを確認
    public function confirm_dispatch(){
        // sessionがないときはエラーページへ
        DispatchHelpers::check_confirm_data_exisits();
        // 表示
        return Inertia::render("ProjectOperator/ProjectDispatch/ConfirmDispatch",[
            "what"=>"案件担当",
            "type"=>"割り当ての重複確認",
            "prefix"=>"project_operator",
            "sameProjectsData"=>session("same_projects_data"),
            "sameTownsData"=>session("same_towns_data"),
            "sameTownsDataInFiles"=>session("same_towns_data_in_files"),
        ]);
    }





    // 重複可能性がある案件をどうするかの確認からの決定
    public function confirm_dispatch_post(ConfirmRequest $request){

         // sessionがないときはエラーページへ
        DispatchHelpers::check_confirm_data_exisits();

        // ProjectImportにて新案件は新案件ナンバーを追加、既存案件の場合は最終期限を直す
        // DistributionPlanImportにて、重複ないものは記入、重複あるものは同じ案件No.を追加、新案件の場合はProjectのIdを上記に連動して取得
        // Importの消去
        CheckFlow::after_confirm_flow($request->newProjects ?? []);

        return redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"project_operator.project_overview","linkPageInJpn"=>"確認ページ"]);
    }


}
