<?php

// 対象となるdateの幅となるqueryを返す
// Distribution_assignmentsならdate,Distribution_plansならstart_date(他のモデルも追加される可能性あり)
namespace App\Support\Common;

use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Log;

class GetDateRangeQuery{
    public static function get_date_range_query($query,$date_sets,$date_column){

        if(!in_array($date_column,["start_date","date"])){
            throw new BusinessException("想定されたカラム名ではありません");
        }

        return
            $query->where(function($query)use($date_column,$date_sets){
            // 基本的には単日のdateが配列内部にあるとき
            $query->whereIn($date_column,$dates=array_keys($date_sets))
            // 複数日可能な案件(end_dateがnullではない)かつdate_setsの最小値より後で、かつdateがdate_setsも最大値より小さいとき
            ->orWhere(function($inner_query)use($date_column,$dates){
               $inner_query
                ->where("end_date", ">=", min($dates))
                ->where($date_column, "<=", max($dates));
             });
            });
    }
}
