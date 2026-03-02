<?php

namespace App\Actions\ProjectOperator;

// 案件割り当てページの表示に関する操作
class DispatchFormData{

    // 案件の開始と終了日の候補のselect
    public static function get_select_dates(){
        $now=now()->copy();
        // 開始日は2日前〜10日後
        $startDateLists=array_reduce(range(-2,10),function($carry,$item)use($now){
            $new_date=$now->copy()->addDay($item);
            return [
                ...$carry,
                $new_date->format("Y-m-d")=>$new_date->format("n月j日")
            ];
        },[]);
        // 終了日は本日~50日後
        $endDateLists=array_reduce(range(0,51),function($carry,$item)use($now){
            $new_date=$now->copy()->addDay($item);
            return [
                ...$carry,
                $new_date->format("Y-m-d")=>$new_date->format("n月j日")
            ];
        },[]);

        return ["start"=>$startDateLists,"end"=>$endDateLists];
    }


}
