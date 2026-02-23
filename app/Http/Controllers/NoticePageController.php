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
        return Inertia::render("Information",[
        "message"=>session("information_message") ?? "お知らせはありません",
        "linkRouteName"=>session("linkRouteName") ?? "top_page",
        "linkPageInJpn"=>session("linkPageInJpn") ?? "トップページ",
        "routeParams"=>session("routeParams") ?? [],
        ]);
    }

    // エラーページへ
    public function view_error(Request $request){
        // エラーセッションがない場合(直接入力を想定)はトップページへ移動
        if(!$request->session()->has("error_message")){
            return redirect()->route("top_page");
        }

        // sessionがある際は、そのエラーを表示
        return Inertia::render("Error",[
            "message"=>session("error_message")
        ]);
    }
}
