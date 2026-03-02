<?php
namespace App\Actions\WholeData;

use Illuminate\Support\Facades\DB;
use App\Models\Place;
use App\Support\Common\PlaceHelpers;

//営業所の登録と取り出し
class Places{

    // 登録
    public static function register_place($request){
        DB::transaction(function()use($request){
            $place=new Place;
            $place->place_name=$request->place;
            $place->red=$request->colors["red"];
            $place->green=$request->colors["green"];
            $place->blue=$request->colors["blue"];
            $place->save();
        });
    }

}
