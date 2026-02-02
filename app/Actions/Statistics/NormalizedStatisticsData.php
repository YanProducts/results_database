<?php
    // その地名を正規化する
    namespace App\Actions\Statistics;
    use App\Constants\Statistics;

    class NormalizedStatisticsData{
        // 全体の流れ
        public static function normalized_flow($csv_data){

        // データ訂正系
        $modified_data=array_map(function($each_data){
            // 世帯数系統の-とX(秘匿)をゼロに直す
            $each_data=self::zero_numberling($each_data);
            // 丁目の「漢数字＋丁目」を数字に直す
            $each_data["town"]=self::modify_town_character($each_data["hyosyo"],$each_data["town"]);
            // ~丁で終わっている地名を直す(＊現時点では堺のみ)
            // 上記から内部呼び出し？
            $each_data["town"]=self::modify_cho_end_pattern($each_data["city"],$each_data["town"]);
            return $each_data;
         },$csv_data);

         //hyosoが1(市全体データ)と3(全丁目のデータ)は省く
         $modified_data=self::filtered_irregular_hyosyo_data($modified_data);
         // 表層の項目は省く
         $modified_data=self::remove_hyosyo_data($modified_data);
        // 飛地のデータをまとめる
         $modified_data=self::gropu_tobichi_data($modified_data);

          return $modified_data;
        }

        // 世帯数系統の-とX(秘匿)をゼロに直す
        private static function zero_numberling($data){
            $data["household"]=str_replace(["-","X"],0,$data["household"]);
            $data["apartment"]=str_replace(["-","X"],0,$data["apartment"]);
            $data["detached"]=str_replace(["-","X"],0,$data["detached"]);
            return $data;
        }

        // 丁目の「漢数字＋丁目」を数字に直す
        private static function modify_town_character($hyoso,$town){
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
        private static function modify_cho_end_pattern($city,$town){

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

        //hyosoが1(市全体データ)と3(全丁目のデータ)は省く
        private static function filtered_irregular_hyosyo_data($base_data){
            return array_filter($base_data,fn($data)=>!in_array(intval($data["hyosyo"]),[1,3]));
        }

        // 表層の項目は省く
        private static function remove_hyosyo_data($base_data){
                // hyosoの項目は除く
                return array_map(function($data){
                    unset($data["hyosyo"]);
                    return $data;
                },$base_data);
        }


        // 飛地のデータをまとめる
        private function gropu_tobichi_data($base_data){
                // クエリビルダではなくコレクションに対するgroupByは、まとめたものに該当するものを配列で格納した配列で返る
                // groupByのキーはstringである必要があるので、各要素からキーとなる文字列を作成して返す
                return
                collect($base_data)->groupby(fn($r)=>$r["pref"]."|".$r["city"]."|".$r["town"])->map(fn($row)=>[
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
        }


        }

