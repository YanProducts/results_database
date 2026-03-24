<?php

// ファイルの操作をまとめる
namespace App\Utils;
class FileHelper{
    // BOMの除去
    public static function remove_BOM($file){
        // バリデーションの段階でfileかどうかは確認済
        return (preg_replace('/^\xEF\xBB\xBF/', '', file_get_contents($file->getRealPath())));
    }

    // BOMを削除したポインタを返す
    public static function get_non_BOM_pointer($file){
            // 中身を取得してBOMを外して変換
            $contents=self::remove_BOM($file);

            // tpmfile()はOSに一時的にファイルを作り、ポインタを返し、自動消去するメソッド
            $tmp_handler = tmpfile();
            fwrite($tmp_handler, $contents);
            rewind($tmp_handler);//ファイルポインタを先頭に戻す
            return $tmp_handler;
    }
}
