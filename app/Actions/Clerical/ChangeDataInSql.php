<?php
// SQLデータの変更
namespace App\Actions\Clerical;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use App\Exceptions\BusinessException;

class ChangeDataInSql{
    public static function change_is_complete($id){
        // すでにtry=catchの中にいる
            DB::transaction(function()use($id){
              $project_matches_id=Project::where("id",$id)->get();
              //バリデーション終了後、SQL到達の前段階の間に更新されたとき
              if($project_matches_id->isEmpty()){
                throw new BusinessException("projectChange");
              }

              //   該当idの完成フラグを反転
              $project_matches_id->is_complete=!$project_matches_id->is_complete;
              $project_matches_id->save();
            });
    }
}
