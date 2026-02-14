<?php

namespace App\Http\Controllers\Config;

use App\Actions\Statistics\GetFileData;
use App\Actions\Statistics\InsertSql;
use App\Actions\Statistics\NormalizeStatisticsData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utils\Session;
use Illuminate\Support\Facades\Log;

class StatisticsController extends Controller
{
    //統計処理を登録するコントローラー(ミドルウェアで開発環境は除外済み)

    // 統計データをSQLへ
    public function insert_household_data(){

            // 大量にSQL登録が必要なため
            ini_set('max_execution_time', 120);

            // ファイルデータの取得(解析できない値の除外も行う)
            $file_data_array=GetFileData::get_file_data();

            // ファイルデータの変換(SQL登録に即した形にする)
            $normalized_data_array=NormalizeStatisticsData::normalized_flow($file_data_array);

            // sqlへの挿入(トランザクションは内部で投げてエラーを返す)
            InsertSql::insert_sql($normalized_data_array);


            // お知らせページへ
            // トップに行くか別のページに行くかを変更できるようにすること！！！！
            return redirect()->route("view_information")->with(["information_message"=>"登録完了しました！"]);

        }
}
