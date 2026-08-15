<?php

// 重複案件のcheckに関する一連の流れ(コントローラーから引き渡し)

namespace App\Actions\ProjectOperator\Dispatch\CheckDispatch;
use App\Actions\ProjectOperator\Dispatch\CheckDispatch\ReadSql as CheckRead;
use App\Actions\ProjectOperator\Dispatch\CheckDispatch\Create as CheckCreate;
use App\Actions\ProjectOperator\Dispatch\CheckDispatch\Delete as CheckDelete;
use App\Actions\ProjectOperator\Dispatch\StoreDispatch;
use App\Actions\ProjectOperator\Dispatch\CheckDispatch\ReadFiles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Flow{

    // ファイルが読み込まれてから最初の重複チェック
    public static function check_flow($project_name_and_towns,$place_id){

    // project_name_and_townsは[テーマ名]=> ["main"=>["project_names"=>"","date_town_sets"=>"","sub"=>["ptojrct_name"と"date_town_sets"がいくつかの配列]]のデータ取得

        // データの初期化(その人が投稿したimportsのテーブルの削除&)
        CheckDelete::automatic_delete_from_same_user();

        // 同じ案件の可能性があるものを返す(１ヶ月以内は問答無用で「同じ」)
        $same_projects_data=CheckRead::check_same_project_data($project_name_and_towns);

        // 同じ案件候補で同じ町目が既に登録されているものを返す(分けていく場合はエラーではないので確認へ)
        $same_towns_data=CheckRead::check_same_town_data($project_name_and_towns);


        // 同じ投稿の内部で同じ町目が重なっているものはないか(同じ投稿で同じ案件は確実に1ヶ月以内のため同じと認識)
        $same_towns_data_in_files=ReadFiles::check_same_projects_and_towns_in_files($project_name_and_towns);


        // どちらかが引っ掛かれば確認ページに行くので、両方のデータを一時保存に挿入
        if(!empty($same_projects_data) || !empty($same_towns_data) || !empty($same_towns_data_in_files)){
            // 誰からの登録か(これが残っている状態で次回のdispatchはできないようにする)
            $auth_id=Auth::user()->id;
            // 一時保存データ登録
            CheckCreate::store_project_imports($project_name_and_towns,$same_projects_data,$auth_id);
            // 確認テーブルに保存(プロジェクト＆町目で必要な全てのデータを一時挿入)
            CheckCreate::store_plan_imports($project_name_and_towns,$same_towns_data,$place_id,$auth_id);
            // 結果テーブルに保存
            CheckCreate::store_total_imports($project_name_and_towns,$place_id,$auth_id);
        }

        return [$same_projects_data,$same_towns_data,$same_towns_data_in_files];
    }

    // 確認後の流れ
    public static function after_confirm_flow($new_projects){

        DB::transaction(function()use($new_projects){

            // 既に存在しているものとの重複
            // 新案件かどうかを考慮しての案件の更新
            StoreDispatch::upsert_after_confirmation_to_projects($new_projects);
            // 町目の更新(新案件の場合は新しい案件ナンバーを入れ、同じ場合は同町目フラグナンバーを更新)
            // 新たなファイル内部での重複も同じメソッドでできる(projectIdとaddressIdとplaceIdが同じ時にsameProjectFlagを更新)
            StoreDispatch::upsert_after_confirmation_to_plans($new_projects);

            // 合計のImportの更新($project_nameからproject_idの最新のものを取得)
            StoreDispatch::update_total_data_from_import();

            // importとsessionの消去
            CheckDelete::automatic_delete_from_same_user();
        });
    }
}
