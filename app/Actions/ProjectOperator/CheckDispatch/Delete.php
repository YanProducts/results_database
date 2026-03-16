<?php
// プロジェクトや町目が既存のものと同じかの確認
namespace App\Actions\ProjectOperator\CheckDispatch;

use App\Models\DistributionPlanImport;
use App\Models\ProjectImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Delete{
    // 同じユーザーからアップされた「同じ案件かの確認」の段階で止まっている情報がある場合は消去
    public static function automatic_delete_from_same_user(){
        // 登録しているユーザーのid
        $user_id=Auth::user()->id;
        // projectの同じか確認のもの
        ProjectImport::where("created_by",$user_id)->delete();
        // プロジェクトと町目のセットが同じか確認のもの
        DistributionPlanImport::where("created_by",$user_id)->delete();
    }
}
