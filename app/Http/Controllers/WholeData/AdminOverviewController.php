<?php

namespace App\Http\Controllers\WholeData;

use App\Actions\WholeData\Overview;
use App\Http\Controllers\Controller;
use App\Support\WholeData\OverViewHelpers;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class AdminOverviewController extends Controller
{
    //現在の登録状況のチェック(whatにはuserやplaceなどが入り、何もない場合は全て見せる)
    public static function admin_overview($type){
        // データの取得
        [$user_data_sets,$place_data_sets]=Overview::get_data_in_sql($type);

        // レンダリング
        return Inertia::render("WholeData/AdminOverview",[
            // テーマ(なくても良いが仕様を合わせるため)
            "what"=>"登録状況確認",
            // ユーザーか場所か両方か
            "type"=>OverViewHelpers::get_page_type_name($type),
            // ユーザーの一覧
            "userDataSets"=>$user_data_sets,
            // ユーザーのテーブル用のキーの日本語名との対応
            "userKeyInJpn"=>Overview::get_user_key_name(),
            // 営業所の一覧
            "placeDataSets"=>$place_data_sets,
            // 営業所のテーブル用のキーの日本語名との対応
            "placeKeyInJpn"=>Overview::get_place_key_name(),
        ]);
    }
}
