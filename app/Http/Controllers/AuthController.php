<?php

namespace App\Http\Controllers;

use App\Actions\Auth\Login;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\AuthTypeRequest;
use Inertia\Inertia;
use App\Support\Auth\UserRoleResolver;
use App\Http\Requests\Auth\RegisterRequest;
use App\Actions\Auth\Register;
use App\Http\Requests\Auth\LoginRequest;
use App\Support\Auth\RedirectTopPage;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthController extends Controller
{

    //登録ページへ
    public function show_register(AuthTypeRequest $request){

        return Inertia::render("Auth/Register",[
            // 内部でページの日本語と英語の配列が格納される
            "pageNameSets"=>UserRoleResolver::get_auth_page_type($request)
        ]);
    }

    // 登録の投稿
    public function post_register(RegisterRequest $request){
            // ロール名の取得
            $role=UserRoleResolver::get_auth_page_type($request)["prefix"];
            // whole_dataの際は仮登録
            if($role=="whole_data"){
                Register::register_onetime_whole_data_flow($request);
                return redirect()->route("view_information")->with(["information_message"=>"メールを送信しました\n記載のURLから本登録を行なってください"]);
            // whole_data以外は登録されていたら本登録
            }else{
                Register::register_user_data($role,$request);
                return redirect()->route("view_information")->with(["information_message"=>"登録完了しました"]);
            }
    }

    // ログインページへ
    public function show_login(AuthTypeRequest $request){
        return Inertia::render("Auth/Login",[
            // 内部でページの日本語と英語の配列が格納される
            "pageNameSets"=>UserRoleResolver::get_auth_page_type($request)
        ]);
    }

    // ログインの投稿
    public function post_login(LoginRequest $request){
        // ログインを試みて、無理ならログインページへ
        if (Login::attempt_login($request)){
            // sessionの再生
            $request->session()->regenerate();
            // それぞれのトップページへ
            return redirect()->intended(RedirectTopPage::redirect_top_page($request->route()->getName()));
        }
        return back()->withErrors([
                "userName" => "認証できませんでした"
            ]);
    }

    // パスワード変更のページへ
    public function show_pass_change(AuthTypeRequest $request){
        return Inertia::render("Auth/PassChange",[
            // 内部でページの日本語と英語の配列が格納される
            "pageNameSets"=>UserRoleResolver::get_auth_page_type($request)
        ]);
    }

    // パスワード変更
    public function post_pass_change(){

    }

    // メールから本登録(全般統括者のみ)
    public function create_whole_data_administer(Request $request){
            // 本登録
            Register::register_whole_data($request);
            // お知らせへ
            return redirect()->route("view_information")->with(["information_message"=>"登録完了しました"]);
    }


    // （退会は全体運営者から行う）
}
