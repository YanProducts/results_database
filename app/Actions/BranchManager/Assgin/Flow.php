<?php

// 営業所からスタッフ割り当ての流れ
namespace App\Actions\BranchManager\Assgin;

use App\Models\BranchManagerList;
use Illuminate\Support\Facades\Auth;

class Flow{
    // 現在、営業所に来ている案件と、現在のスタッフ取得
    public static function get_projects_and_staffs_in_branch(){

        // 現在ログインしている担当のの営業所のid取得
        $place_id=BranchManagerList::where("id",Auth::user()->authable_id)->value("place_id");

        // 5日後までの日付の取得


        // main_projetcs_and_townsはキーに5日先までの日付、valueは1：キーにメイン案件名、valueに町目名(同案件フラグナンバーが多数ある時は「期間〜回目で表示」)
        // sub_projects_and_townsはキーにメイン案件名、valueにtownを入れる
        // staffsはキーに5日先までの日付、valueに5日先までの出席スタッフを入れる(取り急ぎ所属スタッフを全取得)
        // return [$main_projects_and_towns,$sub_projects_and_towns,$staffs];
    }
}
