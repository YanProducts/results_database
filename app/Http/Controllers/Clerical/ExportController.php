<?php

namespace App\Http\Controllers\Clerical;

use App\Actions\Clerical\GetDataInSql;
use App\Constants\Date;
use App\Http\Controllers\Controller;
use App\Models\DistributionAssignment;
use App\Models\DistributionPlan;
use App\Models\DistributionRecord;
use App\Models\Project;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use PHPUnit\TextUI\Configuration\Constant;

class ExportController extends Controller
{
   // 入力担当が現時点で記録されているデータを確認、エクスポートか自分で記録追加かを決める
    public function management_data(){

        // SQLからデータを取得
        [$project_sets,$town_counts,$reported_counts]=GetDataInSql::get_data_in_sql();

        // データをUI用に変換
        $projects_in_sql=GetDataInSql::data_change($project_sets,$town_counts,$reported_counts);

        // 案件の確認(プロジェクトId=>プロジェクト名/締切日/営業所に振られた町目数/記入された町目数/全員の配 布数)
        return Inertia::render("Clerical/ManagementData",["projectsInSql"=>$projects_in_sql]);
    }

    // 報告書エクスポート
    public function export_report(){

    }

    // 発注書エクスポート確認
    public function export_purchase_order(){

    }

}
