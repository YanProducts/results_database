<?php

// 住所に関するモデルのヘルパー

namespace App\Support\CommonModelHelpers;

use App\Exceptions\BusinessException;
use App\Models\Address;

class AddressHelpers{
    // 市と町からidを返す(見つからなければnull)
    public static function get_id_from_city_and_town($city,$town){
        return Address::where([
                                "city"=>$city,
                                "town"=>$town
                                ])->value("id") ?? throw new BusinessException($city.$town."という住所は存在しません");
    }

    // 住所が存在するか
    public static function is_address_exists($city,$town){
        return Address::where([
                        "city"=>$city,
                        "town"=>$town
                        ])->exists();
    }



}
