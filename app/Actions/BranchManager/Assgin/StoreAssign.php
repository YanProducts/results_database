<?php
// Assignで投稿されたデータをSQLに入れる過程でのメソッド
namespace App\Actions\BranchManager\Assgin;

use App\Actions\BranchManager\Assgin\CheckAssign\Delete;
use App\Models\DistributionAssignImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StoreAssign{
    // スタッフを町目に当てはめる
    //allData以下は入れ子の配列。その配列下のplan_idsにはdistribution_planのidが格納。そのidにはプロジェクトのidも紐づけられている
    public static function assign_staffs_to_plans($date,$all_data){
        // すでに重複確認は終えているのでimportではなくassignmentに入れる
        DB::transaction(function()use($date,$all_data){
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


    // 重複(町目の複数人もしくは複数日分割)ありの場合の登録
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
           Delete::delete_imports_by_auth_user();
       });
    }



}
