<?php
    // その地名を正規化する
    namespace App\Actions\Statistics;
    use App\Actions\Statistics\Domain\TownNameNormalizer;
    use App\Actions\Statistics\Domain\DataNumberNormalizer;
    use App\Actions\Statistics\Domain\HasHyosyoNormalizer;

    class NormalizeStatisticsData{
        // 全体の流れ
        public static function normalized_flow($csv_data){

        // データ訂正系
        $modified_data=array_map(function($each_data){
            // 世帯数系統の-とX(秘匿)をゼロに直す
            $each_data=DataNumberNormalizer::normalize_number($each_data);
            // 丁目の「漢数字＋丁目」を数字に直す
            $each_data["town"]=TownNameNormalizer::modify_town_character($each_data["hyosyo"],$each_data["town"]);
            // ~丁で終わっている地名を直す(＊現時点では堺のみ)
            $each_data["town"]=TownNameNormalizer::modify_cho_end_pattern($each_data["city"],$each_data["town"]);
            return $each_data;
         },$csv_data);

         //hyosoが1(市全体データ)と3(全丁目のデータ)は省く
         $modified_data=HasHyosyoNormalizer::filtered_irregular_hyosyo_data($modified_data);
         // 表層の項目は省く
         $modified_data=HasHyosyoNormalizer::remove_hyosyo_data($modified_data);
        // 飛地のデータをまとめる
         $modified_data=DataNumberNormalizer::group_tobichi_data($modified_data);

          return $modified_data;
        }




        }

