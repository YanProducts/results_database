<?php

use App\Http\Controllers\NoticePageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// それぞれの内容を取得
require __DIR__."/each_parts/branch_manager.php";
require __DIR__."/each_parts/clerical.php";
require __DIR__."/each_parts/field_staff.php";
require __DIR__."/each_parts/project_operator.php";
require __DIR__."/each_parts/whole_data.php";

// 市町村データなど根幹のデータ登録。アップ時には載せない
require __DIR__."/config_parts/handle_city_data.php";

//webミドルウェアが適用される(CSRFTokenも適用)function郡(基本全て)
Route::middleware(['web'])->group(function(){
    // トップページ(機能選択)
    Route::get("topPage",function(){
        return Inertia::render("TopPage");
    })
    ->name("top_page");

    // 完了などの情報伝達ページ
    Route::get("information",[NoticePageController::class,"view_information"])->name("view_information");

    // エラーページへ
    Route::get("error",[NoticePageController::class,"view_error"])->name("view_error");
});
