<?php
namespace App\Actions\WholeData;

use Illuminate\Support\Facades\DB;
use App\Models\Place;

//営業所の登録
class RegisterPlaces{

    // 登録
    public static function register_place($request){
        DB::transaction(function()use($request){
            $place=new Place;
            $place->place_name=$request->place;
            $place->color["red"]=$request->red;
            $place->color["green"]=$request->green;
            $place->color["blue"]=$request->blue;
            $place->save();
        });
    }
}
