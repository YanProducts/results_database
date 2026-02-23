<?php
// 権利がいくつかのroleに横断する場合

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FieldStaffs\WriteReportController;


//webミドルウェアが適用される(CSRFTokenも適用)function郡(基本全て)

// 認証関連
Route::middleware(['web'])
      ->group(function(){
        // 営業所と案件と入力の各担当に許可（案件を営業所単位で入力）
        Route::middleware(["redirectUnAuth","redirectUnMatchedRole:branch_manager,project_operator,clerical"])
            ->group(function(){
                Route::controller(WriteReportController::class)
                  ->group(function(){

                });
    });
});
