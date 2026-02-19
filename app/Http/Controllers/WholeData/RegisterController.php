<?php

namespace App\Http\Controllers\WholeData;

use App\Actions\WholeData\RegisterPlaces;
use App\Http\Controllers\Controller;
use App\Http\Requests\WholeData\ProvisionRequest;
use App\Http\Requests\WholeData\RegisterPlacesRequest;
use Inertia\Inertia;
use App\Support\Auth\UserRoleResolver;
use Illuminate\Support\Facades\Log;

// wholeDataから各ユーザーの名前を登録していくコントローラー(パスワード等は別途Authで格納)
class RegisterController extends Controller
{
    // 営業所の登録ページへ
    public function register_places(){
        return Inertia::render("WholeData/RegisterPlaces",[
            "what"=>"全体統括",
            "type"=>"営業所登録"
        ]);
    }

    // 営業所の登録
    public function register_places_post(RegisterPlacesRequest $request){
        // 営業所登録
        RegisterPlaces::register_place($request);
        // お知らせへ
        return redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"whole_data.provision","linkPageInJpn"=>"/各担当の登録"]);
    }

    // ユーザー名とroleなどを事前登録するコントローラー
    public function provision(){
        return Inertia::render("WholeData/Provision",[
            // すべてのroleを英語=>日本語の配列で取得
            "roleSets"=>UserRoleResolver::get_all_values(),
            "what"=>"全体統括",
            "type"=>"ユーザーの登録"
        ]);
    }
    // ユーザー名とroleなどを事前登録するコントローラー(post)
    public function provision_post(ProvisionRequest $request){

    }

}
