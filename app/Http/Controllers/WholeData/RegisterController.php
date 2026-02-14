<?php

namespace App\Http\Controllers\WholeData;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Support\Auth\UserRoleResolver;

// wholeDataから各ユーザーの名前を登録していくコントローラー(パスワード等は別途Authで格納)
class RegisterController extends Controller
{
    // ユーザー名とroleなどを登録するコントローラー
    public function select(){
        return Inertia::render("WholeData/Register",[
            // すべてのroleを英語=>日本語の配列で取得
            "roleSets"=>UserRoleResolver::get_all_values()
        ]);
    }
}
