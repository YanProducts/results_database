<?php

// スタッフの報告書作成時用に、メイン案件の名前をsame_project_flagやround_numberに応じて変更(双方ともsql上の値ではなくその回数ごとの登場回数で比較)
namespace App\Support\FieldStaff;

use App\Exceptions\BusinessException;

class ChangeProjectNameForView{
    public static function get_project_name_for_view($main_project_name,$round_index,$round_length){

        // round_numberによる表示わけ
        return
            match(true){
            $round_length==1=>$main_project_name,
            $round_length==2=>match(true){
                                    $round_index==1=>$main_project_name."：旧案件",
                                    $round_index==2=>$main_project_name."：新案件",
                                    $round_index>3=>throw new BusinessException("round_index is unExpected"),
                                },
            $round_length>2=>$main_project_name."：".$round_index."番目に古い案件"
        };

    }
}
