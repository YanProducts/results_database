<?php

// 通知ページに向かうコントローラー
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class NoticePageController extends Controller
{
    //お知らせページへ
    public function view_information(){

        Log::info("ルーティング内部".session("information_message"));

        return Inertia::render("Information",[
        "message"=>session("information_message") ?? "お知らせはありません"]);
    }

    // エラーページへ
    public function view_error(){
        // エラーセッションがない場合(直接入力を想定)はトップページへ移動
        if(!session()->has("error_message")){
            return redirect()->route("top_page");
        }

        // sessionがある際は、そのエラーを表示
        return Inertia::render("Error",[
            "message"=>session("error_message")
        ]);
    }
}
