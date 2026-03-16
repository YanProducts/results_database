<?php

// 住所に関するモデルのヘルパー

namespace App\Support\CommonModelHelpers;
use App\Models\Address;

class AddressHelpers{
    // 市と町からidを返す
    public static function get_id_from_city_and_town($city,$town){
        return Address::where([
                                "city"=>$city,
                                "town"=>$town
                                ])->value("id");
    }
}
