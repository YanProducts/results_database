<?php

namespace App\Http\Controllers\BranchManager;

use App\Actions\BranchManager\Assgin\Management\AssignOverview;
use App\Actions\BranchManager\Assgin\Management\ResetAssign;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchManager\ResetAssignRequest;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AssignManagementContorller extends Controller
{

    // スタッフに割り当てた案件の一覧
    public function assign_overview(){
        return Inertia::render("BranchManager/Overview/StaffAssignOverview",[
            "what"=>"営業所担当",
            "type"=>"案件一覧",
            // 「日付=>この営業所に振られたproject(plan)=>そのprojectを配布する人」という配列で取得
            "allData"=>AssignOverview::get_overview_data()
        ]);
    }

    // スタッフへの割り当ての編集。当日まで変更可能に。
    // ただし配布データが存在すれば変更不可にする(その場合は報告書の編集より提出。備考欄に記入)
    public function edit_assign($date){

    }

    // 割り当てリセット。当日までリセット可能に。
    // ただし配布データが存在すれば変更不可にする(その場合は報告書の編集より提出。備考欄に記入)
    public function reset_assign(ResetAssignRequest $request){

        try{
            // 削除の流れ
            ResetAssign::reset_process($request->date);
        }catch(\Throwable $e){
            // エラー処理はbootstrap/app.phpに任す
            throw new BusinessException($e instanceof BusinessException ? $e->getMessage() : "予期せぬエラーです");
        }

        return redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"branch_manager.assign_overview","linkPageInJpn"=>"現在の割り当ての確認"]);
    }
}
