<?php
// プロジェクトや町目が既存のものと同じかの確認
namespace App\Actions\ProjectOperator\CheckDispatch;

use App\Utils\Session;
use App\Models\DistributionPlanImport;
use App\Models\ProjectImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Delete{
    // 同じユーザーからアップされた確認データの消去(本登録終了後、もしくは「同じ案件かの確認」の段階で止まっている情報)
    public static function automatic_delete_from_same_user(){
        // 確認中のsessionの削除
        Session::delete_sessions(["same_projects_data","same_towns_data"]);
        // 登録しているユーザーのid
        $user_id=Auth::user()->id;
        // projectの同じか確認のもの
        ProjectImport::where("created_by",$user_id)->delete();
        // プロジェクトと町目のセットが同じか確認のもの
        DistributionPlanImport::where("created_by",$user_id)->delete();
    }
}
