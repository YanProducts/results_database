<?php

namespace App\Http\Controllers;

use App\Actions\Auth\Login;
use App\Actions\Auth\Logout;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\AuthTypeRequest;
use Inertia\Inertia;
use App\Support\Auth\UserRoleResolver;
use App\Http\Requests\Auth\RegisterRequest;
use App\Actions\Auth\Register;
use App\Http\Requests\Auth\LoginRequest;
use App\Support\Auth\RedirectTopPage;
use App\Exceptions\BusinessException;

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
        // sessionの再生(その後に作成した方が良い)
        $request->session()->regenerate();
        // ログインを試みて、無理ならログインページへ
        if (Login::attempt_login($request)){
            // それぞれのトップページへ
            if(str_contains($request->route()->getName(),"whole_data")){
                // 全般管理の場合(authがない状態で試みたページとは関係なくトップへ)
                return redirect()->route("whole_data.select");
            }else{
                // roleがある場合はauthがない状態で保存されたページへ
                return redirect()->intended(RedirectTopPage::redirect_top_page($request->route()->getName()));
            }
        }
        return back()->withErrors([
                "userName" => "パスワードが違います"
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

    // ログアウト
    public function logout(Request $request){
        //それぞれのrouteのprefixの取得
        $prefix=UserRoleResolver::get_auth_page_type($request)["prefix"];

        // ログアウト(whole_dataが入っているかいないかで操作を変更)
        Logout::logout($prefix);

        // session再生成
        $request->session()->regenerate();

        // ログインページへのリダイレクト
        return redirect()->route($prefix.".login");
    }

    // （退会は全体運営者から行う）
}
