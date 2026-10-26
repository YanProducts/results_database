<?php
// Assignで投稿されたデータをSQLに入れる過程でのメソッド
namespace App\Actions\BranchManager\Assgin;

use App\Actions\BranchManager\Assgin\DuplicatedChek\Common\Delete as CommonDelete;
use App\Exceptions\BusinessException;
use App\Models\DistributionAssignImport;
use App\Models\DistributionProjectAssignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StoreAssign{
    // スタッフを町目に当てはめる(シンプル、詳細とも同じ)
    //allData以下は入れ子の配列。その配列下のplan_idsにはdistribution_planのidが格納。そのidにはプロジェクトのidも紐づけられている
    public static function assign_plans_to_staffs($date,$all_data,$from_simple_flag){
        // すでに重複確認は終えているのでimportではなくassignmentに入れる
        DB::transaction(function()use($date,$all_data,$from_simple_flag){
            $new_assigned_data=[];
            // 複数回取得するので先に抽出
            foreach($all_data as $data){
                $staff_id=$data["staffId"];
                foreach($data["planIds"] as $plan_id){
                    $new_assigned_data[]=[
                        // 日付とスタッフとplanのidを挿入(このセットが重複したらupdateせずにエラーを出す)
                        "date"=>$date,
                        "staff_id"=>$staff_id,
                        "plan_id"=>$plan_id,
                        "from_simple_flag"=>$from_simple_flag,
                        "created_by"=>Auth::user()->id,
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now(),
                    ];
                }
            }
            // 複数行一括で挿入
            DB::table("distribution_assignments")->insert($new_assigned_data);
        });
    }


    // 重複(町目の複数人もしくは複数日分割)ありの場合の本登録
    // すでにImportには登録されており、それをコピーする
    public static function commit_duplicated_imports(){
        // 重複を含むデータ
       $distribution_assign_imports=DistributionAssignImport::where("created_by",Auth::user()->id)->get()->toArray();

       // 上記の時刻系列の変更
       $distribution_assign_to_sql_data=array_map(fn($each_imports) => [...$each_imports,"created_at"=>Carbon::now(),"updated_at"=>Carbon::now()],$distribution_assign_imports);

       //挿入(staff_idは違うのでユニーク制約にはかからない,statusはデフォルトで0）
       DB::transaction(function()use($distribution_assign_to_sql_data){
           DB::table("distribution_assignments")->insert($distribution_assign_to_sql_data);

           // データの削除
           CommonDelete::delete_imports_by_auth_user();
       });
    }

    // 案件ごと指定におけるSQL登録
    public static function assign_project_to_staffs($date,$all_data){
        try{
          DB::transaction(function()use($date,$all_data){
            $array_for_insert=[];
            foreach($all_data as $data){
                if(empty($data["projectPlanIds"])){
                    continue;
                }
                foreach($data["projectPlanIds"] as $project_plan_id){
                    $array_for_insert[]=[
                      "project_plan_id"=>$project_plan_id,
                      "staff_id"=>$data["staffId"],
                      "date"=>$date,
                      //end_dateは現時点では全てnull。今後仕様を変えていくかを含めて要検討
                      "created_by"=>Auth::user()->id,
                      "created_at"=>Carbon::now(),
                      "updated_at"=>Carbon::now(),
                    ];
                }
            }
            DistributionProjectAssignment::insert($array_for_insert);
          });
        }catch(\Throwable $e){
            Log::info($e->getMessage());
            throw new BusinessException("データ挿入の際にエラーが生じました");
        }
    }

}
