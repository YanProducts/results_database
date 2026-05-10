<?php
// 入力担当によるSQLデータの変更
namespace App\Actions\Clerical;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Log;

class ChangeDataInSql{

   // 案件が完成しているか否か
    public static function change_is_complete($id){

        // すでにtry=catchの中にいる
            DB::transaction(function()use($id){
             //   getなら配列、firstならsave()はできずにupdateになり現時点での値を取得は難しい。findなら配列の0番目にせず一発で可能
              $project_matches_id=Project::find($id);

              //バリデーション終了後、SQL到達の前段階の間に更新されたとき
              if(empty($project_matches_id)){
                throw new BusinessException("projectChange");
              }

              //   該当idの完成フラグを反転
              $project_matches_id->is_complete=!$project_matches_id->is_complete;
              $project_matches_id->save();
            });
    }
}
