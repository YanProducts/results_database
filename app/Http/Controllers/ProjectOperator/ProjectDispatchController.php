<?php

namespace App\Http\Controllers\ProjectOperator;

use App\Actions\ProjectOperator\CheckDispatch\Delete as CheckDelete;
use App\Actions\ProjectOperator\CheckDispatch\Flow as CheckFlow;
use App\Http\Controllers\Controller;
use App\Support\CommonModelHelpers\PlaceHelpers;
use App\Actions\ProjectOperator\StoreDispatch;
use App\Exceptions\BusinessException;
use App\Http\Requests\ProjectOperator\ConfirmRequest;
use App\Http\Requests\ProjectOperator\DispatchRequest;
use App\Models\DistributionPlanImport;
use App\Models\ProjectImport;
use App\Support\ProjectOperator\DispatchCSVProcessor;
use App\Support\ProjectOperator\DispatchHelpers;
use App\Utils\Session;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ProjectDispatchController extends Controller
{
    // 営業所(外注含む)へ振る案件を選択する画面を表示
    public function dispatch_project(){

        // 現在の確認ページ用のsessionと、そのユーザーが投稿した「確認中」のテーブルは消去する
        CheckDelete::automatic_delete_from_same_user();

        return Inertia::render("ProjectOperator/ProjectDispatch/SendProjectToBranch",[
            // 営業所リスト
            "placeSets"=>PlaceHelpers::get_registered_places(),
            "type"=>"案件→営業所"
        ]);
    }

    // 営業所(外注含む)へ振る案件投稿→以前と同じものか確認
    public function dispatch_project_post(DispatchRequest $request){

        // CSVから案件名=>[town,start,end]の入れ子配列の取得
        $project_name_and_towns=DispatchCSVProcessor::get_data_in_files($request->fileSets);

        //placeはすでにplaceがid
        $place_id=$request->place;

        //重複チェックの一連の流れを行い、重複データを変換(この過程でsqlデータを初期化する)
        [$same_projects_data,$same_towns_data]=CheckFlow::check_flow($project_name_and_towns,$place_id);

        if(!empty($same_projects_data) || !empty($same_towns_data)){
            // フラッシュセッションだとバリデーション時のエラー捕捉がやりにくい
            Session::create_sessions([
                "same_projects_data"=>$same_projects_data,
                "same_towns_data"=>$same_towns_data
            ]);
            // 既存のものと重複可能性がある場合は確認ページへ
            return redirect()->route("project_operator.confirm_dispatch",[
                "same_projects_data"=>session($same_projects_data),
                "same_towns_data"=>session($same_towns_data)
            ]);
        }


        // 既存のものと案件名が重ならないか期間的に同じと思われる場合には登録(request->placeは既にid名)
        StoreDispatch::store_projects_data($project_name_and_towns,$place_id);

        return redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"project_operator.project_overview","linkPageInJpn"=>"確認ページ"]);

    }

    // 重複可能性がある案件をどうするかを確認
    public function confirm_dispatch(){
        // sessionがないときはエラーページへ
        DispatchHelpers::check_confirm_data_exisits();

        // 表示
        return Inertia::render("ProjectOperator/ProjectDispatch/ConfirmDispatch",[
            "what"=>"案件担当",
            "type"=>"割り当ての重複確認",
            "prefix"=>"project_operator",
            "sameProjectsData"=>session("same_projects_data"),
            "sameTownsData"=>session("same_towns_data")
        ]);
    }

    // 重複可能性がある案件をどうするかの確認からの決定
    public function confirm_dispatch_post(ConfirmRequest $request){

         // sessionがないときはエラーページへ
        DispatchHelpers::check_confirm_data_exisits();

        // ProjectImportにて新案件は新案件ナンバーを追加、既存案件の場合は最終期限を直す
        // DistributionPlanImportにて、重複ないものは記入、重複あるものは同じ案件No.を追加、新案件の場合はProjectのIdを上記に連動して取得
        // Importの消去
        CheckFlow::after_confirm_flow($request->newProjects ?? []);

        return redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"project_operator.project_overview","linkPageInJpn"=>"確認ページ"]);
    }

    // 案件の確認
    public function project_overview(){

    }

}
