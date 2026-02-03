<?php

// 地名の数字変換のメソッドまとめ(*Domainとはその場所でしか通じないという意味)
// 呼び出しは別の場所から

namespace App\Actions\Statistics\Domain;

class DataNumberNormalizer{
        // 世帯数系統の-とX(秘匿)をゼロに直す
        public static function normalize_number($data){
            $data["household"]=str_replace(["-","X"],0,$data["household"]);
            $data["apartment"]=str_replace(["-","X"],0,$data["apartment"]);
            $data["detached"]=str_replace(["-","X"],0,$data["detached"]);
            return $data;
        }
        // 飛地のデータをまとめる
        public static function group_tobichi_data($base_data){
                // クエリビルダではなくコレクションに対するgroupByは、まとめたものに該当するものを配列で格納した配列で返る
                // groupByのキーはstringである必要があるので、各要素からキーとなる文字列を作成して返す
                return
                collect($base_data)->groupby(fn($r)=>$r["pref"]."|".$r["city"]."|".$r["town"])->map(fn($row)=>[
                    // pref,city,townは集約しているのでどの項目も同じ
                    "pref"=>$row[0]["pref"],
                    "city"=>$row[0]["city"],
                    "town"=>$row[0]["town"],
                    // コレクションへのgroupbyの内部はコレクションなので->sumも可能
                    "household"=>$row->sum("household"),
                    "apartment"=>$row->sum("apartment"),
                    "detached"=>$row->sum("detached"),
                    "establishment"=>$row->sum("establishment")
                ])->values() //keyを0から順に振り直す(insertは連番数値である必要性)
                ->toArray();
        }
}
