<?php

namespace App\Http\Controllers\Clerical;

use App\Constants\Date;
use App\Http\Controllers\Controller;
use App\Http\Requests\Clerical\PurchaseOrderRequest;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use Illuminate\Http\Request;
use Inertia\Inertia;

// 発注書作成のコントローラー
class PurchaseOrderController extends Controller
{
    //発注書のエクスポートする人と期間の設定する画面表示
    public function export_purchase_order(){

        return Inertia::render("Clerical/PurchaseOrder",[
        "prefix"=>"clerical",
        "what"=>"入力担当",
        "type"=>"発注書作成",
        // スタッフの取得、1:営業所名ごとが外側2:id=>スタッフ名が内側の入れ子の配列
        "staffsGroupByPlaces"=>FieldStaffListHelpers::get_all_staffs_group_by_place_name(true),
        // 初期表示は何ヶ月前からか
        "defaultStartDateForPurchaseLists"=>Date::PurchaseListDefaultMonthsBack
        ]);

    }

    //発注書のエクスポート
    public function export_purchase_order_post(PurchaseOrderRequest $request){

        $is_create="";

        return response()->json(["is_create"=>$is_create]);
    }

}
