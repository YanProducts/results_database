<?php

namespace App\Http\Controllers\BranchManager;

use App\Http\Controllers\Controller;
use App\Support\Common\ModelHelpers\PlaceHelpers;
use Inertia\Inertia;
use App\Support\Common\ModelHelpers\BranchManagerListHelpers;
use App\Actions\Shared\Dispatch\CheckDispatch\Delete as CheckDelete;
use App\Http\Requests\ProjectOperator\DispatchRequest;
use App\Actions\Shared\Dispatch\ProjectDispatchProcedure;
use App\Support\ProjectOperator\DispatchHelpers;
use App\Actions\Shared\Dispatch\CheckDispatch\Flow as CheckFlow;
use Illuminate\Support\Facades\Log;

// 案件を自分で登録する系統
class ProjectHandingController extends Controller
{
    // その営業所における案件の登録(最終的にProjectOperatorのページを使うので、内容自体はProjectDispatchと重なる面が多い。現状短く形式も微妙に異なるのでコピー)
    public function handing_assignment(){

        // 現在の確認ページ用のsessionと、そのユーザー(Auth::user()->idでuser_authsのidで取得しているので消去可能)が投稿した「確認中」のテーブルは消去する
        CheckDelete::automatic_delete_from_same_user();

        return Inertia::render("ProjectOperator/ProjectDispatch/SendProjectToBranch",[
            // その営業所の(Id=>名前)が取得(不要になる可能性あり)
            "placeSets"=>PlaceHelpers::get_place_name_from_ids([BranchManagerListHelpers::get_login_user_place_id()])->first(),
            "type"=>"案件→営業所"
        ]);
    }

    // その営業所における案件登録のpost
    public function handing_assignment_post(DispatchRequest $request){

        // 割り当てが成功か失敗か重複戻しか
        $dispatch_test_result=ProjectDispatchProcedure::project_dispatch_procedure($request->fileSets,"branch_manager",BranchManagerListHelpers::get_login_user_place_id(),);

        return
        match($dispatch_test_result){
            "view_error"=>redirect()->route("view_error"),
            "duplicated"=> redirect()->route("branch_manager.confirm_dispatch"),
            "success"=> redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"branch_manager.top_page","linkPageInJpn"=>"トップ"])
        };

    }

    // 重複可能性がある案件をどうするかを確認(ほとんど同じ内容なので、 ProjectOperatorのをほぼコピー)
    public function confirm_dispatch(){
        // sessionがないときはエラーページへ
        DispatchHelpers::check_confirm_data_exisits();
        // 表示 //project_operatorから借りてくる
        return Inertia::render("ProjectOperator/ProjectDispatch/ConfirmDispatch",[
            "what"=>"案件担当",
            "type"=>"割り当ての重複確認",
            "prefix"=>"branch_manager",
            "sameProjectsData"=>session("same_projects_data"),
            "sameTownsData"=>session("same_towns_data"),
            "sameTownsDataInFiles"=>session("same_towns_data_in_files"),
        ]);
    }


    // 重複可能性がある案件の決定投稿(ほとんど同じ内容なので、 ProjectOperatorのをほぼコピー)
    public function confirm_dispatch_post(){
              // sessionがないときはエラーページへ
        DispatchHelpers::check_confirm_data_exisits();

        // ProjectImportにて新案件は新案件ナンバーを追加、既存案件の場合は最終期限を直す
        // DistributionPlanImportにて、重複ないものは記入、重複あるものは同じ案件No.を追加、新案件の場合はProjectのIdを上記に連動して取得
        // Importの消去
        CheckFlow::after_confirm_flow($request->newProjects ?? []);

        return redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"branch_manager.top_page","linkPageInJpn"=>"トップ"]);
    }



}
