<?php

namespace App\Actions\Statistics;

use Illuminate\Support\Facades\DB;
use App\Models\Address;

// SQLへの登録
class InsertSql{

  // SQLデータに挿入
  public static function insert_sql($normalized_data){

    // ini_setを行ってもデータ量がなお多いので、いくつかに切り分けて処理を行う
    $chunksize=1000;

    // トランザクション(try~catchの中にはいる)
    DB::transaction(function()use($normalized_data,$chunksize){
      // すでに入っているデータは削除(国勢調査の上書きや全ての県をstorageに入れていないことを想定)
      DB::table("addresses")->delete();

      // データを1000ずつ一括入力(すでにカラムは対応させている。時刻系は全て同じ処理になるので手数をかけすぎないために入れない)
      foreach(array_chunk($normalized_data,$chunksize) as $chunked_data){
      Address::insert($chunked_data);
      }
    });
  }

}
