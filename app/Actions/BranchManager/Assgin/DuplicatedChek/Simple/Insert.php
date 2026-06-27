<?php

// 地図番号のみ選択の際の重複(mapの全planIdを全て配布済のとき)にImportに登録
namespace App\Actions\BranchManager\Assgin\DuplicatedChek\Simple;
use App\Models\DistributionAssignImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Insert{
    // 登録
    public static function insert_assign_import($date,$all_data){
        // 挿入用のデータに変更
        $new_import_data=[];
            // 複数回取得するので先に抽出
            foreach($all_data as $data){
                $staff_id=$data["staffId"];
                foreach($data["planIds"] as $plan_id){
                    $new_import_data[]=[
                        // 日付とスタッフとplanのidを挿入(このセットが重複したらupdateせずにエラーを出す)
                        "plan_id"=>$plan_id,
                        "staff_id"=>$staff_id,
                        "date"=>$date,
                        // end_dateをどうする？
                        "end_date"=>"",

                        "from_simple_flag"=>true,
                        "created_by"=>Auth::user()->id,
                        "created_at"=>Carbon::now(),
                        "updated_at"=>Carbon::now(),
                    ];
                }
            }

        // 挿入
        DB::transaction(function()use($new_import_data){
            DB::table("distribution_assignments")->insert($new_import_data);
        });
    }
}
