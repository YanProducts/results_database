<?php

namespace App\Http\Controllers\BranchManager;

use App\Actions\BranchManager\Assgin\Management\AssignOverview;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssignOverviewContorller extends Controller
{

    // スタッフに割り当てた案件の一覧
    public function assign_overview(){
        return Inertia::render("BranchManager/StaffAssignOverview",[
            "what"=>"営業所担当",
            "type"=>"案件一覧",
            // 「日付=>この営業所に振られたproject(plan)=>そのprojectを配布する人」という配列で取得
            "allData"=>AssignOverview::get_overview_data()
        ]);
    }

    // 当日まで変更可能に。
    // ただし配布データが存在すれば変更不可にする(その場合は報告書の編集より提出。備考欄に記入)
    // スタッフへの割り当ての編集
    public function edit_assign($date){

    }
}
