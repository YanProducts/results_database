<?php

namespace App\Actions\Statistics;

use Illuminate\Support\Facades\DB;
use App\Models\Address;

// SQLへの登録
class InsertSql{

  // SQLデータに挿入
  public static function insert_sql($file_data){

   //hyosoが1(市全体データ)と3(全丁目のデータ)は省く
    $filtered_file_data=array_filter($file_data,fn($data)=>!in_array(intval($data["hyosyo"]),[1,3]));

    // hyosoの項目は除く
    $without_hyosyo_data=array_map(function($data){
        unset($data["hyosyo"]);
        return $data;
    },$filtered_file_data);

    // 飛地のデータをまとめる
    // クエリビルダではなくコレクションに対するgroupByは、まとめたものに該当するものを配列で格納した配列で返る
    // groupByのキーはstringである必要があるので、各要素からキーとなる文字列を作成して返す
    $collect_duplicated_data=collect($without_hyosyo_data)->groupby(fn($r)=>$r["pref"]."|".$r["city"]."|".$r["town"])->map(fn($row)=>[
        // pref,city,townは集約しているのでどの項目も同じ
        "pref"=>$row[0]["pref"],
        "city"=>$row[0]["city"],
        "town"=>$row[0]["town"],
        // コレクションへのgropubyの内部はコレクションなので->sumも可能
        "household"=>$row->sum("household"),
        "apartment"=>$row->sum("apartment"),
        "detached"=>$row->sum("detached"),
        "establishment"=>$row->sum("establishment")
    ])->values() //keyを0から順に振り直す(insertは連番数値である必要性)
    ->toArray();

    // ini_setを行ってもデータ量がなお多いので、いくつかに切り分けて処理を行う
    $chunksize=1000;

    // トランザクション(try~catchの中にはいる)
    DB::transaction(function()use($collect_duplicated_data,$chunksize){
      // すでに入っているデータは削除(国勢調査の上書きや全ての県をstorageに入れていないことを想定)
      DB::table("addresses")->delete();

      // データを1000ずつ一括入力(すでにカラムは対応させている。時刻系は全て同じ処理になるので手数をかけすぎないために入れない)
      foreach(array_chunk($collect_duplicated_data,$chunksize) as $chunked_data){
      Address::insert($chunked_data);
      }
    });
  }

}
