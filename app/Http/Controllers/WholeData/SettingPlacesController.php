<?php

namespace App\Http\Controllers\WholeData;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Actions\WholeData\Places;
use App\Http\Requests\WholeData\RegisterPlacesRequest;
use App\Models\Place;

class SettingPlacesController extends Controller
{
    // 営業所の登録ページへ
    public function register_places(){
        return Inertia::render("WholeData/RegisterPlaces");
    }

    // 営業所の登録
    public function register_places_post(RegisterPlacesRequest $request){
        // 営業所登録
        Places::register_place($request);
        // お知らせへ
        return redirect()->route("view_information")->with(["information_message"=>"登録完了しました","linkRouteName"=>"whole_data.provision","linkPageInJpn"=>"各担当の登録"]);
    }

    //編集する営業所の決定
    public static function decide_edit_place($id){
        // その営業所が存在するか
        if(!Place::whereKey($id)->exists()){
            return redirect()->back()->withErrors(["placeError"=>"該当する営業所が見つかりませんでした"]);
        }

        // 表示
        return Inertia::render("WholeData/EditPlace",[
            "type"=>"営業所の編集",
            "id"=>$id
        ]);
    }

}
