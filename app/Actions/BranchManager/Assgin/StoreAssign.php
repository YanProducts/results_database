<?php
// Assignで投稿されたデータをSQLに入れる過程でのメソッド

namespace App\Actions\BranchManager\Assgin;
use App\Models\DistributionAssignment;
use Illuminate\Support\Facades\DB;


class StoreAssign{

    // スタッフを町目に当てはめる
    //allData以下は入れ子の配列。その配列下のplan_idsにはdistribution_planのidが格納。そのidにはプロジェクトのidも紐づけられている
    public static function assign_staffs_to_plans($date,$all_data){

        // すでに重複確認は終えている
        DB::transaction(function(){
            $distribution_assignment=new DistributionAssignment();
            
            $distribution_assignment->save();
        });

    }

}
