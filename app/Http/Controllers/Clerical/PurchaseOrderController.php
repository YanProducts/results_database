<?php

namespace App\Http\Controllers\Clerical;

use App\Actions\Clerical\PurchaseOrder\CreatePurchaseCSVFlow;
use App\Constants\Date;
use App\Http\Controllers\Controller;
use App\Http\Requests\Clerical\PurchaseOrderRequest;
use App\Support\Common\ModelHelpers\FieldStaffListHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    public function create_purchase_order_csv(PurchaseOrderRequest $request){

        // パラメータ取得
        [$staff_id,$start_date,$end_date]=[$request->staffId,$request->startMonth,$request->endMonth];

        // エクスポートまでの処理
        $export_flow_result=CreatePurchaseCSVFlow::create_purchase_procedure($staff_id,$start_date,$end_date);

        // エクスポートできたかの結果を返す(全て終了すればOK。そうでなければエラーの種類があるものはそのエラー、ない場合は予期せぬエラーを返す)
        return response()->json(["ExportFlowResult"=>$export_flow_result]);
    }

    // 発注書の実際のダウンロード(成功：ダウンロードを返す。失敗：Inertiaのエラーを返す)
    public function download_purchase_order(){
        // 内部でreturnの処理は上記に合うように分岐
        return CreatePurchaseCSVFlow::download_purchase_csv();
    }

}
