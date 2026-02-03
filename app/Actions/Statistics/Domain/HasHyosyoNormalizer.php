<?php

// 表層が登録外の時などの変換のメソッドまとめ(*Domainとはその場所でしか通じないという意味)
// 呼び出しは別の場所から

namespace App\Actions\Statistics\Domain;

class HasHyosyoNormalizer{
       //hyosoが1(市全体データ)と3(全丁目のデータ)は省く
        public static function filtered_irregular_hyosyo_data($base_data){
            return array_filter($base_data,fn($data)=>!in_array(intval($data["hyosyo"]),[1,3]));
        }

        // 表層の項目は省く
        public static function remove_hyosyo_data($base_data){
                // hyosoの項目は除く
                return array_map(function($data){
                    unset($data["hyosyo"]);
                    return $data;
                },$base_data);
        }
}
