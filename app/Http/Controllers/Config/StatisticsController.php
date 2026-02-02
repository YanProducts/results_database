<?php

namespace App\Http\Controllers\Config;

use App\Actions\Statistics\GetFileData;
use App\Actions\Statistics\InsertSql;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utils\Session;
use Illuminate\Support\Facades\Log;

class StatisticsController extends Controller
{
    //統計処理を登録するコントローラー(ミドルウェアで開発環境は除外済み)

    // 統計データをSQLへ
    public function insert_household_data(){
        try{

            // 大量にSQL登録が必要なため
            ini_set('max_execution_time', 120);

            // ファイルデータの取得
            $file_data_array=GetFileData::get_file_data();

            // sqlへの挿入(トランザクションは内部で投げてエラーを返す)
            InsertSql::insert_sql($file_data_array);

            // フラッシュセッションに入れてお知らせページへ
            Session::create_flush_sessions(["information_message","登録完了しました\nSQLを確認してください！"]);

            return redirect()->route("view_information");


        }catch(\Throwable $e){

        Log::info($e->getmessage());

            Session::create_flush_sessions(["error_message",$e->getMessage()]);
            Session::create_sessions(["error_message",$e->getMessage()]);
            return redirect()->route("view_error");
        }

    }
}
