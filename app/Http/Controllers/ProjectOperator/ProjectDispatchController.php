<?php

namespace App\Http\Controllers\ProjectOperator;

use App\Http\Controllers\Controller;
use App\Support\Common\PlaceHelpers;
use App\Actions\ProjectOperator\DispatchFormData;
use App\Actions\ProjectOperator\StoreDispatch;
use App\Http\Requests\ProjectOperator\DispatchRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectDispatchController extends Controller
{
    // 営業所(外注含む)へ振る案件を選択する画面を表示
    public function dispatch_project(){

        // 開始日：修正の可能性も考えて(2日前〜10日後)   // 終了日(~50日後)
        $date_sets=DispatchFormData::get_select_dates();

        return Inertia::render("ProjectOperator/ProjectDispatch/SendProjectToBranch",[
            "startDateLists"=>$date_sets["start"],
            "endDateLists"=>$date_sets["end"],
            // 営業所リスト
            "placeSets"=>PlaceHelpers::get_registered_places(),
            "type"=>"割り当て"
        ]);
    }

    // 営業所(外注含む)へ振る案件を決定
    public function dispatch_project_post(DispatchRequest $request){

        Log::info($request);
        StoreDispatch::send_projects_to_branch($request);

        return redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"project_operator.project_overview","linkPageInJpn"=>"確認ページ"]);
    }
}
