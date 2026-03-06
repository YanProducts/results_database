<?php
// 営業所に関するサポート関数

namespace App\Support\Common;
use App\Models\Place as PlaceModel;

class PlaceHelpers{
    // id=>営業所名の配列で取り出し
    public static function get_registered_places(){
      return PlaceModel::pluck('place_name', 'id');
    }
    // 営業所名からIdを取得する
    public static function get_id_from_place_name($place_name){
        return PlaceModel::where("place_name",$place_name)->value("id") ??  throw new \Error("営業所のidが取得できません");
    }
}
