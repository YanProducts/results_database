<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// それぞれの内容を取得
require __DIR__."/each_parts/branch_manager.php";
require __DIR__."/each_parts/clerical.php";
require __DIR__."/each_parts/field_staff.php";
require __DIR__."/each_parts/project_operator.php";
require __DIR__."/each_parts/whole_data.php";

//webミドルウェアが適用される(CSRFTokenも適用)function郡(基本全て)
Route::middleware(['web'])->group(function(){
    // トップページ(機能選択)
    Route::get("topPage",function(){
        return Inertia::render("TopPage");
    })
    ->name("top_page");

    // 完了などの情報伝達ページ
    Route::get("information",function(){
        return Inertia::render("information",[
            "message"=>session("information_message") ?? "お知らせはありません"
        ]);
    })->name("view_information");

    // エラーページ
    Route::get("error",function(){
        return Inertia::render("error",[
            "message"=>session("error_message") ?? "予期せぬエラーです"
        ]);
    })->name("view_error");
});