<?php

namespace App\Http\Controllers\ProjectOperator;

use App\Actions\ProjectOperator\CheckDispatch;
use App\Http\Controllers\Controller;
use App\Support\Common\PlaceHelpers;
use App\Actions\ProjectOperator\DispatchFormData;
use App\Actions\ProjectOperator\StoreDispatch;
use App\Http\Requests\ProjectOperator\DispatchRequest;
use App\Support\ProjectOperator\DispatchCSVProcessor;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectDispatchController extends Controller
{
    // 営業所(外注含む)へ振る案件を選択する画面を表示
    public function dispatch_project(){

        // 開始日：修正の可能性も考えて(2日前〜10日後)   // 終了日(~50日後)
        // $date_sets=DispatchFormData::get_select_dates();

        return Inertia::render("ProjectOperator/ProjectDispatch/SendProjectToBranch",[
            // "startDateLists"=>$date_sets["start"],
            // "endDateLists"=>$date_sets["end"],
            // 営業所リスト
            "placeSets"=>PlaceHelpers::get_registered_places(),
            "type"=>"案件→営業所"
        ]);
    }

    // 営業所(外注含む)へ振る案件投稿→以前と同じものか確認
    public function dispatch_project_post(DispatchRequest $request){

        // CSVから案件名=>[town,start,end]の入れ子配列の取得
        $project_name_and_towns=DispatchCSVProcessor::get_data_in_files($request->fileSets);

        // 同じ案件か違う案件か
        // 同じ案件Idで同じ町目が既に重なっているが良いか？
        if(!empty($another_project_flags=CheckDispatch::check_same_project_data($project_name_and_towns)) || ""){
            // 確認テーブルに保存(プロジェクト＆町目)



            // 既存のものと確認ページへ
            return redirect()->route("",[
                "another_project_flag",$another_project_flags,
                "same_town_and_project_flag",["project"=>"","town"=>""]
            ]);
        }




        // 既存のものと案件名が重ならないか期間的に同じと思われる場合には登録
        StoreDispatch::store_projects_data($project_name_and_towns,PlaceHelpers::get_id_from_place_name($request->place));

        return redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"project_operator.project_overview","linkPageInJpn"=>"確認ページ"]);

    }
}
