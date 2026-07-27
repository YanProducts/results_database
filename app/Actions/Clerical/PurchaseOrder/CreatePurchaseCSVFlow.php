<?php
// スタッフと日付がpostされた後での発注書の作成
namespace App\Actions\Clerical\PurchaseOrder;

use App\Constants\Download;
use App\Exceptions\BusinessException;
use App\Support\Common\CSVExporter;
use App\Utils\DateHelper;
use App\Support\Common\ModelHelpers\DistributionRecordHelpers;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use App\Utils\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreatePurchaseCSVFlow{
    // 発注書作成における全体的な流れ
    public static function create_purchase_procedure($staff_id,$start_date,$end_date){

        try{
                // 2度使うので補足
                $staff_name=FieldStaffListHelpers::get_real_staff_name($staff_id);
                $start_month_name=DateHelper::get_jpn_year_and_month($start_date);
                $end_month_name=DateHelper::get_jpn_year_and_month($end_date);

                // SQLデータの取得(内部でtry~catchされ、「データ取得時エラー」という文言が投げられる)
                $record_of_staff_and_date_range=GetDataInSql::get_data_in_sql($staff_id,$start_date,$end_date);

                // CSV書式への変換(内部でtry~catchされ、「CSV書式への変換」という文言が投げられる)
                $data_lists=FormatData::data_change_for_purchase_order($staff_name,$start_month_name,$end_month_name,$record_of_staff_and_date_range);

                // ファイルの作成
                // データを一時的におくファイルのパス(他の人も使用していた時のことを考え、id名もつけて保存)
                CSVExporter::create_csv_file($data_lists,storage_path(Download::PurchaseCSVFilePath."_".Auth::user()->id.".csv"));

                // ダウンロードファイルをsessionに保持
                Session::create_sessions(["purchase_download_file_name"=>$staff_name."_".$start_month_name. ($start_date!=$end_date ? ("~".$end_month_name) : "")]);

            return "Ok";
        }catch(\Throwable $e){
            return $e instanceof BusinessException ? $e->getMessage(): "予期せぬエラーです";
        }

    }

    // ファイルのダウンロード
    // ダウンロードはresponse自体がdownloadを返してしまうのでaxiosのファイル作成とは別に行う
    public static function download_purchase_csv(){
        try{
            // ダウンロード
            // ファイルの削除はこの内部で行なってくれる
            // ダウンロードできたらattachmentのレスポンスなので見た目上は何も変えらない
            // returnをつけないと、ダウンロード処理を返す操作が実行されない
            return CSVExporter::download_csv_files(session("purchase_download_file_name"),storage_path(Download::PurchaseCSVFilePath."_".Auth::user()->id.".csv"));
        }catch(\Throwable $e){
            // 元のページのInertiaのerrorsに格納
            return redirect()->back()->withErrors(["download"=>$e instanceof BusinessException ? $e->getMessage() :"何らかのエラーです\n失敗が続く場合は作成者にご連絡ください"]);
        }finally{
            // どちらにせよsessionは削除する
            Session::delete_sessions(["purchase_download_file_name"]);
        }

    }

}
