<?php

// スタッフの報告書作成時用に、メイン案件の名前をsame_project_flagやround_numberに応じて変更(双方ともsql上の値ではなくその回数ごとの登場回数で比較)
namespace App\Support\FieldStaff;

use App\Exceptions\BusinessException;

class ChangeProjectNameForView{
    public static function get_project_name_for_view($main_project_name,$same_project_index,$same_project_length,$round_index,$round_length){

        // same_project_flagによる表示わけ
        $key_name_with_same_project_flag=match(true){
            $same_project_length==1=>$main_project_name,
            $same_project_length==2=>match(true){
                                    $same_project_index==1=>$main_project_name."：旧案件",
                                    $same_project_index==2=>$main_project_name."：新案件",
                                    $same_project_index>3=>throw new BusinessException("same_project_index is unExpected"),
                                },
            $same_project_length>2=>$main_project_name."：".$same_project_index."番目に古い案件"
        };

        // round_numberによる表示わけ
        return match(true){
                  $round_length==1=>$key_name_with_same_project_flag,
                  $round_length>1=>$key_name_with_same_project_flag."：".$round_index,
        };
    }
}
