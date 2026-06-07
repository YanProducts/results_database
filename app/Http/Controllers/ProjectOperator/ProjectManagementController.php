<?php

namespace App\Http\Controllers\ProjectOperator;

use App\Actions\ProjectOperator\Management\GetProjectDataInSql;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProjectManagementController extends Controller
{
    // 現在振り分けた案件の確認
    public function project_overview(){

    // "案件名","開始日","終了日","割当済町目数","配布済町目数","設定部数","現在配布部数",を、それぞれ配列で取得(営業所などはさらに内部でどの町を誰に振ったか等で分ける)
     $project_data=GetProjectDataInSql::get_all_data_in_sql();


      return Inertia::render("ProjectOperator/ProjectManagement/ProjectOverview",[
        "type"=>"案件の確認(割当済町目の締切が1か月以上前のものを除く)",
        "projectData"=>$project_data
      ]);
    }

    // 特定案件の編集の表示
    public function edit_project_top($edit_id){

    }
    // 案件の編集の投稿
    public function edit_project_post(){

    }
}
