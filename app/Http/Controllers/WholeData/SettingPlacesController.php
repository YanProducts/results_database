<?php

namespace App\Http\Controllers\WholeData;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Actions\WholeData\Places;
use App\Http\Requests\WholeData\RegisterPlacesRequest;

class SettingPlacesController extends Controller
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
        Places::register_place($request);
        // お知らせへ
        return redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"whole_data.provision","linkPageInJpn"=>"各担当の登録"]);
    }
}
