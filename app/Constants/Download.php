<?php

// ダウンロードするファイルパスやダウンロー時のファイル名など
namespace App\Constants;

use App\Http\Requests\Clerical\PurchaseOrderRequest;

class Download{

    // レポートファイルのパス（ここにstorage_pathとログインユーザー名をつけて作成）
    public const ReportCSVFilePath="app/tmp/distribution_report";

    // レポートファイルのダウンロード名（ここに時刻.csvをつけて作成）
    public const ReportCSVFileName="案件レポート";

    // 発注書の一時保存ファイル名(ここにstorage_pathをつけて作成)
    public const PurchaseCSVFilePath="app/tmp/purchase.csv";

 }
