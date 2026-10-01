<?php

namespace App\Actions\Shared;

use App\Actions\ProjectOperator\Dispatch\StoreDispatch;
use App\Utils\Session;
use App\Actions\ProjectOperator\Dispatch\CheckDispatch\Flow as CheckFlow;
use App\Support\ProjectOperator\DispatchCSVProcessor;
use App\Exceptions\BusinessException;
use App\Enums\UserRole;
use App\Support\Common\ModelHelpers\BranchManagerListHelpers;

// 案件の割り当ての流れ(案件担当と営業所担当で共有)
class ProjectDispatchPrecedure{

    public static function project_dispatch_procedure($fileSets,$role,$place_id){
        try{
            // CSVからテーマ名=>["main"=>["projects"=>"","date_town_sets"=>"","sub"=>["ptojrct_name"と"date_town_sets"がいくつかの配列]]のデータ取得
            // この内部でインストールされたファイルの中身のエラーチェック
            $project_name_and_towns=DispatchCSVProcessor::get_data_in_files($fileSets);
        }catch(\Throwable $e){
            Session::create_sessions(["error_message"=>$e instanceof BusinessException ? $e->getMessage() : "予期せぬエラーです","back_route"=>UserRole::top_page_route_name($role)]);
            // ここはコントローラーへ返す
            return "view_error";
        }


        //重複チェックの一連の流れを行い、重複データを変換(この過程でsqlデータを初期化する,合計テーブルも入れる)
        [$same_projects_data,$same_towns_data,$same_towns_data_in_files]=CheckFlow::check_flow($project_name_and_towns,$place_id);


        if(!empty($same_projects_data) || !empty($same_towns_data) || !empty($same_towns_data_in_files)){
            // フラッシュセッションだとバリデーション時のエラー捕捉がやりにくい
            Session::create_sessions([
                "same_projects_data"=>$same_projects_data,
                "same_towns_data"=>$same_towns_data,
                "same_towns_data_in_files"=>$same_towns_data_in_files
            ]);
            // 既存のものと重複可能性がある場合は確認ページへ
            return "duplicated";
        }

        // 既存のものと案件名が重ならないか期間的に同じと思われる場合には登録(request->placeは既にid名)
        StoreDispatch::store_projects_data($project_name_and_towns,$place_id);

        return "success";
    }
}
