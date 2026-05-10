<?php
// CSVエクスポート時の流れ
namespace App\Actions\Clerical;

use App\Constants\Download;
use App\Http\Requests\Clerical\CSVExportRequest;
use App\Support\Common\CSVExporter;
use Illuminate\Support\Facades\Log;

class CSVExportFlow{

    // 報告書csv作成
    public static function create_reports_csv_flow($project_ids){
        // n+1対策のため。SQLにあるproject_idを含むplan_id、それと対応するaddressのid=>住所のセット、plan_idと対応する配布結果(スタッフと日付含む)を前もって返す
        [$plans_in_project_ids,$project_sets,$address_sets,$distribution_record_sets]=GetDataInSql::get_detailed_planned_data_by_project_ids($project_ids);

        // プランにあるデータ(案件=>[[町目名:,部数:日付:(複数日の場合あり),スタッフ:(複数人の場合あり)]の入れ子配列])
        //CSVのためにもう1ついくか？
        $formatted_projects_and_records_data=FormatData::data_change_for_csv_report_data($plans_in_project_ids,$project_sets,$address_sets,$distribution_record_sets);

        // ファイルパスを取得し、csvファイルの作成
        $filepath=storage_path(Download::ReportCSVFilePath);
        CSVExporter::create_csv_file($formatted_projects_and_records_data,$filepath,",");
    }


}
