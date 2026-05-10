<?php

namespace App\Http\Controllers\Clerical;

use App\Actions\Clerical\ChangeDataInSql;
use App\Actions\Clerical\CSVExportFlow;
use App\Actions\Clerical\FormatData;
use App\Actions\Clerical\GetDataInSql;
use App\Constants\Download;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Clerical\CSVExportRequest;
use App\Http\Requests\Clerical\ToggleCompleteRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

// 報告されたデータに処置を施すコントローラー
class DataManagementController extends Controller
{
   // 入力担当が現時点で記録されているデータを確認、エクスポートか自分で記録追加かを決める
    public function management_report(){

        // SQLからデータを取得
        [$project_sets,$town_count_sets,$reported_count_sets]=GetDataInSql::get_aggregated_data_in_sql();


        // データをUI用に変換
        $projects_in_sql=FormatData::data_change_for_management_page($project_sets,$town_count_sets,$reported_count_sets);

        // 案件の確認(プロジェクトId=>プロジェクト名/締切日/営業所に振られた町目数/記入された町目数/全員の配 布数)
        return Inertia::render("Clerical/ManagementData",[
        "prefix"=>"clerical",
        "what"=>"入力担当",
        "type"=>"案件データ一覧",
        "projectsInSql"=>$projects_in_sql
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
            return response()->json(["is_create",true]);
        }catch(\Throwable $e){
            Log::info($e->getMessage());
            // ファイル作成失敗の場合
            return response()->json(["is_create",false]);
        }
    }

    //報告書CSVエクスポート
    public function download_report_csv(){
        $file_path=storage_path(Download::ReportCSVFilePath);
        if(file_exists($file_path)){
            return response()->download($file_path,Download::ReportCSVFileName.Carbon::today()->toDateString());
        }else{
            return redirect()->back()->withErrors(["download"=>"ファイル作成ができておりません\n失敗が続く場合は作成者にご連絡ください"]);
        }
    }

    // 発注書エクスポート確認
    public function export_purchase_order(){

    }

    // 案件の完成/編集可能の変換
    public function toggle_complete(ToggleCompleteRequest $request){
        // プロジェクトの該当idのis_completeを取得し変換
        // 存在確認はバリデーション済
        $id=$request->id;
        try{
            ChangeDataInSql::change_is_complete($id); //SQLに反映
        }catch(\Throwable $e){
            // エラーが投げられた時
            if($e instanceof BusinessException){
                return response()->json(["fetchError",$e->getMessage()]);
            }
            return response()->json(["fetchError"=>"undefined"]);
        }
        //
        return response()->json(["isOK"=>true]);

    }

}
