<?php

namespace App\Http\Controllers\Clerical;

use App\Actions\Clerical\DataManagement\ChangeDataInSql;
use App\Actions\Clerical\DataManagement\CSVExportFlow;
use App\Actions\Clerical\DataManagement\GetDataInSql;
use App\Actions\Clerical\DataManagement\FormatData;

use App\Constants\Date;
use App\Constants\Download;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Clerical\CSVExportRequest;
use App\Http\Requests\Clerical\ToggleCompleteRequest;
use App\Support\Common\CSVExporter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

// 報告されたデータに処置を施すコントローラー
class DataManagementController extends Controller
{
   // 入力担当が現時点で記録されているデータを確認、エクスポートか自分で記録追加かを決める
    public function management_report($end_offset=null){

        // SQLからデータを取得
        [$project_sets,$town_count_sets,$reported_count_sets]=GetDataInSql::get_aggregated_data_in_sql($end_offset);

        // データをUI用に変換
        $projects_in_sql=FormatData::data_change_for_management_page($project_sets,$town_count_sets,$reported_count_sets);

        // 案件の確認(プロジェクトId=>プロジェクト名/締切日/営業所に振られた町目数/記入された町目数/全員の配 布数)
        return Inertia::render("Clerical/ManagementData",[
        "prefix"=>"clerical",
        "what"=>"入力担当",
        "type"=>"案件データ一覧",
        "projectsInSql"=>$projects_in_sql,
        "archiveCutOffDate"=>Carbon::now()->addDays($end_offset ?? (Date::EndOffsetForClericalExport)-1)->format("n月j月")
        ]);
    }

    // 報告書CSV作成(Inertiaは)
    public function create_report_csv(CSVExportRequest $request){

        // CSV出力する案件の取得
        $project_ids=$request->idSets;

        try{
            // 案件をCSVデータに変換して保存
            CSVExportFlow::create_reports_csv_flow($project_ids);

            // ひとまずは成功jsonを返す(Inertiaではレスポンスを期待され、「ファイルをダウンロード」という処理ができない)
            return response()->json(["is_create"=>true]);
        }catch(\Throwable $e){
            Log::info($e->getMessage());
            // ファイル作成失敗の場合
            return response()->json(["is_create"=>false]);
        }
    }

    //報告書CSVエクスポート
    public function download_report_csv(){
        try{
            // ファイルの削除はこの内部で行なってくれる
            // ダウンロードできたらattachmentのレスポンスなので見た目上は何も変えらない
            return CSVExporter::download_csv_files(Download::ReportCSVFileName.Carbon::today()->toDateString(),storage_path(Download::ReportCSVFilePath."_".Auth::user()->id.".csv"));
        }catch(\Throwable $e){
            return redirect()->back()->withErrors(["download"=>"ファイル作成ができておりません\n失敗が続く場合は作成者にご連絡ください"]);
        }
    }

    // 案件の完成/編集可能の変換
    public function toggle_complete(ToggleCompleteRequest $request){
        // プロジェクトの該当idのis_completeを取得し変換
        // 存在確認はバリデーション済
        $id=$request->projectId;

        try{
            ChangeDataInSql::change_is_complete($id); //SQLに反映
        }catch(\Throwable $e){
            // エラーが投げられた時
            if($e instanceof BusinessException){
                return response()->json(["fetchError",$e->getMessage()]);
            }
            return response()->json(["fetchError"=>"undefined"]);
        }
        // fetchだからこう返す
        return response()->json(["isOK"=>true]);
    }

}
