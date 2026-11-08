<?php
// 日毎とスタッフごとのデータの取得
// 通常にassignされた場合と、プロジェクトごとのassignとで分ける

namespace App\Actions\Shared\GetDataInStaffAndDate;

use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Log;

class GetDataProcedure{
  public static function get_data_procedure($staff_id,$date_sets){

        // 通常盤の割当データ取得
        [$data_in_staff_and_date,$from_simple_flag,$submitted_dates]=GetDataInStaffAndDate::get_assigned_data($staff_id,$date_sets);

        // 案件のみでセットしたスタッフの日毎のデータの取得
        [$project_only_data_in_staff_and_date,$project_only_submitted_dates]=GetProjectOnlyDataInStaffAndDate::get_assigned_data($staff_id,$date_sets);

        // それぞれdateごとにsort //両方ある場合はエラー
        // 1：バリデーション(両者のキーの数が同じか)、keysは同じか？
        //２：どちらかのkeysを取得し、両方のデータを1つずつ見て、片側にデータがあればそれを挿入。両方にデータがあればエラー
        [$return_data_in_staff_and_date,$return_submitted_dates,$project_only_assigned_dates]=self::merge_data($date_sets,$data_in_staff_and_date,$project_only_data_in_staff_and_date,$submitted_dates,$project_only_submitted_dates);

        // 返却
        return [$return_data_in_staff_and_date,$from_simple_flag,$return_submitted_dates,$project_only_assigned_dates];
  }

// 日毎に通常盤と案件のみ版との両方をチェック
// 日毎に両方のデータが入っていればエラーを返す
// dateごとの配列に並べる
 public static function merge_data($date_sets,$data_in_staff_and_date,$project_only_data_in_staff_and_date,$submitted_dates,$project_only_submitted_dates){

    $return_data_sets=[];//割り当て済みデータ
    $return_submitted_dates=[];//どちらかが提出済の日にち
    $project_only_assigned_dates=[]; //案件から割り当てた日付の配列

    foreach(array_keys($date_sets) as $date){

        // 通常盤と案件版に分けての割当の元データの取得
        $normal=$data_in_staff_and_date[$date] ?? [];
        $project_only=$project_only_data_in_staff_and_date[$date] ?? [];

        // 上記に重複データがあればエラー
        if(!empty($normal) && !empty($project_only)){
            Log::info("案件と通常の両方のデータが同じ日に存在");
            throw new BusinessException("エラーが生じています\n作成者に確認してください");
        }

        // どちらかに記録が既にあればsubmit側に入れて、その後の処理はスキップ
        if($submitted_dates->contains($date) || $project_only_submitted_dates->contains($date)){
            $return_submitted_dates[]=$date;
            $return_data_sets[$date]=[];
            continue;
        }


        // 挿入(project_onlyのcollectは既に分岐済)
        $return_data_sets[$date]=!empty($normal) ? $normal : $project_only;

        // 案件のみ版で登録されたもの
        if(!empty($project_only)){
            $project_only_assigned_dates[]=$date;
        }


    }

    return [$return_data_sets,$return_submitted_dates,$project_only_assigned_dates];
 }


}
