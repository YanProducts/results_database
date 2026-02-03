<?php

namespace App\Actions\Statistics;

use App\Actions\Statistics\Domain\TownNameNormalizer;
use App\Constants\Statistics;
use Illuminate\Support\Facades\Log;

// ファイルのデータを配列にして変換する
class GetFileData{

  // ファイルデータの取得
  public static function get_file_data(){

    // すでにtry~catchの内部にいる

    // 全データの配列の下準備
    $all_statistics_data=[];

    // それぞれの県ごとのファイル
    foreach(Statistics::Prefectures as $pref_in_eng=>$pref_in_jpn){

      // ファイルのパス
      $path=storage_path("app/private/statistics/".$pref_in_eng.".txt");

      // ファイルがあるかどうか
      if(!file_exists($path)){
        throw new \Error("ファイルがありません");
      }

      //ファイルの中のデータを取得
      $data_in_file=file($path);

    // ファイルを展開する
    // それぞれのファイルの１行目はタイトルなので省く
    // それぞれのファイルの行の必要なデータを取得し、配列の最後に県の名前入れて返す
      $pref_data_array=array_map(function($each_file_row)use($pref_in_jpn){
        $modified_file_row=self::inner_row_change($each_file_row,$pref_in_jpn);
        return $modified_file_row;
      },array_filter($data_in_file,fn($each_default_file_row,$index)=>$index!=0,ARRAY_FILTER_USE_BOTH));

     // 手書きで直すデータが存在する場合(内部でエラーを投げる)
     // csvパースを２度繰り返すのを避けるため、配列化した後のデータで検証
      self::check_irregular_data($pref_in_jpn,$pref_data_array);

      // その県のデータを全データに追加
      $all_statistics_data=[...$all_statistics_data,...$pref_data_array];
    }

    // 全データの配列を返す
    return $all_statistics_data;
  }


 // 手書きでデータを直すべき項目が存在する場合
 private static function check_irregular_data($pref,$pref_data_array){
    // 「二十丁目」もしくは「二十丁」で終わっているデータがある場合
    TownNameNormalizer::check_over20_town_name($pref,$pref_data_array);
 }




  // ファイルの内側の行を当てはまるものに変化
  private static function inner_row_change($each_file_row,$pref){

    // 文字化けをなおす
    $encoded_each_file_row = mb_convert_encoding($each_file_row, 'UTF-8', 'SJIS-win');

    // txtだがCSV形式で出力されているため、カンマで区切られた形式で取得
    $csv_parsed_cols=str_getcsv($encoded_each_file_row,",");

    //処理(ここでは必要な値のみを取り残し、付け加えるのみを行う)
    return[
      // 表層(市全体か、丁目がない街か、丁目全体か、丁目ごとか)
      "hyosyo"=>$csv_parsed_cols[1],
      // 県
      "pref"=>$pref,
      // 市
      "city"=>$csv_parsed_cols[2],
      // 町目
      "town"=>$csv_parsed_cols[3],
      // 世帯
      "household"=>$csv_parsed_cols[7],
      // 集合
      "apartment"=>$csv_parsed_cols[10],
      // 戸建
      "detached"=>$csv_parsed_cols[8],
      // 事業所(このファイルでは取得できないので0とする)
      "establishment"=>0
    ];
  }

}
