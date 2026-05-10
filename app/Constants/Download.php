<?php

// ダウンロードするファイルパスやダウンロー時のファイル名など
namespace App\Constants;

class Download{
    // レポートファイルのパス（ここにstorage_pathをつけて作成）
    public const ReportCSVFilePath="app/tmp/distribution_report.csv";
    // レポートファイルのダウンロード名（ここに時刻.csvをつけて作成）
    public const ReportCSVFileName="案件レポート";

 }
