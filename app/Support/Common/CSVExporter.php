<?php

// CSVのエクスポートへの準備
namespace App\Support\Common;

use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Log;

class CSVExporter{

    // CSVファイルの作成(filepathに作成される)
    public static function create_csv_file($data_lists,$filepath,$separator=","){


    Log::info($data_lists);


        try{
            $fp=fopen($filepath,"w");
            // Excel向けUTF-8 BOM
            fwrite($fp, "\xEF\xBB\xBF");

            foreach($data_lists as $line){
                fputcsv($fp,$line,$separator);
            }

            fclose($fp);
        }catch(\Throwable $e){
            Log::info($e->getMessage());
            throw new BusinessException("ファイル作成時のエラーです");
        }
    }

    // CSVファイルのダウンロード
    public static function download_csv_files($filename_after_download,$now_filename){
            if (!file_exists($now_filename)) {
                // 呼び出し元にエラーを投げる
                throw new BusinessException("ファイル作成ができておりません\n失敗が続く場合は作成者にご連絡ください");
            }
            return response()
                ->download($now_filename, $filename_after_download)
                ->deleteFileAfterSend(true);
        }


}
