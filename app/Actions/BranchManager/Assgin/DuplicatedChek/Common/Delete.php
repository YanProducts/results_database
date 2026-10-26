<?php
// 重複チェックの削除のメソッド
namespace App\Actions\BranchManager\Assgin\DuplicatedChek\Common;

use App\Models\DistributionAssignImport;
use Illuminate\Support\Facades\Auth;
use App\Models\DistributionProjectAssignmentImport;

class Delete{
    // Authユーザーからのデータの削除(案件版と通常盤の両方)
    public static function delete_imports_by_auth_user(){
        DistributionAssignImport::where("created_by",Auth::user()->id)->delete();
        DistributionProjectAssignmentImport::where("created_by",Auth::user()->id)->delete();

    }
}
