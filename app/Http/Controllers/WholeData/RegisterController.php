<?php

namespace App\Http\Controllers\WholeData;

use App\Http\Controllers\Controller;
use App\Http\Requests\WholeData\ProvisionRequest;
use Inertia\Inertia;
use App\Support\Auth\UserRoleResolver;

// wholeDataから各ユーザーの名前を登録していくコントローラー(パスワード等は別途Authで格納)
class RegisterController extends Controller
{
    // 営業所の登録ページへ

    // 営業所の登録


    // ユーザー名とroleなどを事前登録するコントローラー
    public function provision(){
        return Inertia::render("WholeData/Provision",[
            // すべてのroleを英語=>日本語の配列で取得
            "roleSets"=>UserRoleResolver::get_all_values(),
            "what"=>"ユーザーの登録"
        ]);
    }
    // ユーザー名とroleなどを事前登録するコントローラー(post)
    public function provision_post(ProvisionRequest $request){

    }

}
