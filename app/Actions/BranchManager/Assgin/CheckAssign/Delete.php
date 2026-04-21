<?php
// 重複チェックの削除のメソッド
namespace App\Actions\BranchManager\Assgin\CheckAssign;

use App\Models\DistributionAssignImport;
use Illuminate\Support\Facades\Auth;

class Delete{
    // Authユーザーからのデータの削除
    public static function delete_imports_by_auth_user(){
        DistributionAssignImport::where("created_by",Auth::user()->id)->delete();
    }
}
