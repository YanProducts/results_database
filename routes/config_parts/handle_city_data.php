<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Config\StatisticsController;
use Inertia\Inertia;

// 大阪と兵庫のデータをSQL登録(配布数は別途存在するため、データを削除して上書き)
Route::middleware(["web","onlyLocal"])
  ->group(function(){
   Route::get("statistics/insert_households",[StatisticsController::class,"insert_household_data"])
   ->name("");
 });
