<?php

// CSVのエクスポートへの準備
namespace App\Support\Common;
class CSVExporter{

    // CSVファイルの作成(filepathに作成される)
    public static function create_csv_file($data_lists,$filepath,$separator=","){
        $fp=fopen($filepath,"w");
        // Excel向けUTF-8 BOM
        fwrite($fp, "\xEF\xBB\xBF");
        foreach($data_lists as $line){
            fputcsv($fp,$line,$separator);
        }
        fclose($fp);
    }

    // CSVファイルのダウンロード
    public static function download_csv_files($filename_after_download,$now_filename){
        header('Content-Type: text/csv');
        header("Content-Disposition:attachment;filename=".$filename_after_download);
        readfile($now_filename);
        exit;
    }


}
