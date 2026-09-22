<?php

namespace App\Http\Controllers\Clerical;

use App\Actions\Clerical\DataManagement\WriteReport\ChangeDistributionDataByProject;
use App\Actions\Clerical\DataManagement\WriteReport\GetDistributionDataInTheProjects;
use App\Http\Controllers\Controller;
use App\Http\Requests\Clerical\WriteReportRequest;
use App\Support\Common\ModelHelpers\ProjectHelpers;
use App\Support\Common\ModelHelpers\ProjectPlannedCountHelpers;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;


// 入力担当が行う報告書入力作業
class WriteReportController extends Controller
{
    //報告書入力ページへ
    public function write_report($edit_id){
        // projectIdに当てはまるplanされたデータをとってくる
        // planId=>[町目、営業所、[現在部数、スタッフ、日付]]
        $report_data_in_the_project=GetDistributionDataInTheProjects::get_data_for_editting_report_by_the_project($edit_id);

        // このルートはgetで来ているので、そのままInertia::renderで大丈夫
        return Inertia::render("Clerical/WriteReport",[
                "type"=>"報告書記入",
                "reportDataInTheProject"=>$report_data_in_the_project,
                "projectSets"=>[$edit_id=>ProjectHelpers::get_project_name_from_id($edit_id)],
                "totalPlannedCount"=>ProjectPlannedCountHelpers::get_total_planned_counts_by_the_project($edit_id) //現時点の設定部数合計
        ]);

    }

    // 報告書入力ページにpostする
    public function write_report_post(WriteReportRequest $request){

        // データの登録
        // 投稿データはprojectIdとchangedDifference(planIdとcountDifference)
        ChangeDistributionDataByProject::change_data_procedure($request->projectId,$request->changedDifference);


        return redirect()->route("view_information")->with(["information_message"=>"送信完了しました","linkRouteName"=>"clerical.management_report"]);


    }
}
