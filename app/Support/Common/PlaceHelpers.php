<?php
// 営業所に関するサポート関数

namespace App\Support\Common;
use App\Models\Place as PlaceModel;

class PlaceHelpers{
    // id=>営業所名の配列で取り出し
    public static function get_registered_places(){
      return PlaceModel::pluck('place_name', 'id');
    }
}
