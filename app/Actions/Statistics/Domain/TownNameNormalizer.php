<?php

// 地名変換のメソッドまとめ(*Domainとはその場所でしか通じないという意味)
// 呼び出しは別の場所から

namespace App\Actions\Statistics\Domain;
use App\Constants\Statistics;

class TownNameNormalizer{

        // 丁目の「漢数字＋丁目」を数字に直す
        public static function modify_town_character($hyoso,$town){
                // 表層が「4」つまり「丁目」単位の時のみのを切り出す
                if($hyoso!=4){
                    return $town;
                }

                return
                    match($town){
                        // 二十丁目以上は前段階でチェックし、あれば前もって「手動でファイルを直せ」と指示を出す(大阪と兵庫の最高は岸和田土生町の１３丁目)
                        str_ends_with($town,"十丁目")=>str_replace("十丁目","１０",$town),
                        str_ends_with($town,"十一丁目")=>str_replace("十一丁目","１１",$town),
                        str_ends_with($town,"十二丁目")=>str_replace("十二丁目","１２",$town),
                        str_ends_with($town,"十三丁目")=>str_replace("十三丁目","１３",$town),
                        str_ends_with($town,"十四丁目")=>str_replace("十四丁目","１４",$town),
                        str_ends_with($town,"十五丁目")=>str_replace("十五丁目","１５",$town),
                        str_ends_with($town,"十六丁目")=>str_replace("十六丁目","１６",$town),
                        str_ends_with($town,"十七丁目")=>str_replace("十七丁目","１７",$town),
                        str_ends_with($town,"十八丁目")=>str_replace("十八丁目","１８",$town),
                        str_ends_with($town,"十九丁目")=>str_replace("十九丁目","１９",$town),
                        str_ends_with($town,"一丁目")=>str_replace("一丁目","１",$town),
                        str_ends_with($town,"二丁目")=>str_replace("二丁目","２",$town),
                        str_ends_with($town,"三丁目")=>str_replace("三丁目","３",$town),
                        str_ends_with($town,"四丁目")=>str_replace("四丁目","４",$town),
                        str_ends_with($town,"五丁目")=>str_replace("五丁目","５",$town),
                        str_ends_with($town,"六丁目")=>str_replace("六丁目","６",$town),
                        str_ends_with($town,"七丁目")=>str_replace("七丁目","７",$town),
                        str_ends_with($town,"八丁目")=>str_replace("八丁目","８",$town),
                        str_ends_with($town,"九丁目")=>str_replace("九丁目","９",$town),
                    };
        }


        // ~丁で終わっている地名を直す(＊現時点では堺のみ)
        public static function modify_cho_end_pattern($city,$town){

            if(count(array_filter(Statistics::ChoEndCities,fn($cho_end_city)=>str_starts_with($city,$cho_end_city)))==0){
                return $town;
            }

            return
                match($town){
                    // 二十丁以上は前段階でチェックし、あれば前もって「手動でファイルを直せ」と指示を出す(大阪と兵庫の最高は岸和田土生町の１３丁)
                    str_ends_with($town,"十丁")=>str_replace("十丁","１０",$town),
                    str_ends_with($town,"十一丁")=>str_replace("十一丁","１１",$town),
                    str_ends_with($town,"十二丁")=>str_replace("十二丁","１２",$town),
                    str_ends_with($town,"十三丁")=>str_replace("十三丁","１３",$town),
                    str_ends_with($town,"十四丁")=>str_replace("十四丁","１４",$town),
                    str_ends_with($town,"十五丁")=>str_replace("十五丁","１５",$town),
                    str_ends_with($town,"十六丁")=>str_replace("十六丁","１６",$town),
                    str_ends_with($town,"十七丁")=>str_replace("十七丁","１７",$town),
                    str_ends_with($town,"十八丁")=>str_replace("十八丁","１８",$town),
                    str_ends_with($town,"十九丁")=>str_replace("十九丁","１９",$town),
                    str_ends_with($town,"一丁")=>str_replace("一丁","１",$town),
                    str_ends_with($town,"二丁")=>str_replace("二丁","２",$town),
                    str_ends_with($town,"三丁")=>str_replace("三丁","３",$town),
                    str_ends_with($town,"四丁")=>str_replace("四丁","４",$town),
                    str_ends_with($town,"五丁")=>str_replace("五丁","５",$town),
                    str_ends_with($town,"六丁")=>str_replace("六丁","６",$town),
                    str_ends_with($town,"七丁")=>str_replace("七丁","７",$town),
                    str_ends_with($town,"八丁")=>str_replace("八丁","８",$town),
                    str_ends_with($town,"九丁")=>str_replace("九丁","９",$town),
                };
        }

         // 手書きでデータを直すべき項目が存在する場合
        public static function check_over20_town_name($pref,$pref_data_array){
            // 「二十丁目」もしくは「二十丁」で終わっているデータがある場合
            // collectは外側にしか効かないので内部は配列のまま
            if(collect($pref_data_array)->contains(function($data){
                return str_ends_with($data["town"],"二十丁目") || str_ends_with($data["town"],"二十丁");
            })){
                throw new \Exception($pref."ファイルに「二十丁目」もしくは「二十丁」以上のデータが存在しています\n手書き修正か、プログラム修正が必要です");
            }
        }


}
