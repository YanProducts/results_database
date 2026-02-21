<?php
namespace App\Actions\WholeData;

use Illuminate\Support\Facades\DB;
use App\Models\Place;

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

    // id=>営業所名の配列で取り出し
    public static function get_registered_places(){
      return Place::pluck('place_name', 'id');
    }
}
