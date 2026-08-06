<?php

// 住所に関するモデルのヘルパー

namespace App\Support\Common\ModelHelpers;

use App\Constants\Statistics;
use App\Exceptions\BusinessException;
use App\Models\Address;
use App\Utils\Regex;
use Illuminate\Support\Facades\DB;

class AddressHelpers{
    // 市と町からidを返す(見つからなければnull)
    public static function get_id_from_city_and_town($city,$town){
        return Address::where(["city"=>$city,"town"=>$town])->value("id") ?? throw new BusinessException($city.$town."という住所は存在しません");
    }

    // idから市と町のセットを返す
    public static function get_city_and_town_from_id($address_id){
        return Address::select(DB::raw("CONCAT(city, town) as address_name"))->where("id",$address_id)->value("address_name") ?? null;
    }

    // 市もしくは県から始まる住所の配列からid=>住所名の配列を返す
    public static function get_id_name_sets_from_name_array($address_names){
        Address::selectRaw("id,concat('city','town') as address_name,concat('pref','city','town') as full_address_name")->whereIn("address_name",$address_names)->orWhereIn("full_address_name",$address_names)->pluck("address_name","id");
    }

    // 住所idに対する住所名をid=>名前の配列で一括取得(n+1防止)
    public static function get_city_and_town_arrays_key_by_id($ids){
        return
        Address::whereIn("id",$ids)->pluck(DB::raw("CONCAT(city, town) as address_name"),"id")->toArray();
    }

    // 住所idに対する住所名と世帯数のセットをid=>[名前,世帯数セット]の配列で一括取得(n+1防止)
    public static function get_address_name_and_household_set_arrays_key_by_id($ids){
        return
        Address::whereIn("id",$ids)->select("id",DB::raw("CONCAT(city, town) as address_name"),"household","apartment","detached","establishment")->get()->keyBy("id")->toArray();
    }

    // 住所が存在するか
    public static function is_address_exists($city,$town){
        return Address::where(["city"=>$city,"town"=>$town])->exists();
    }

    // idのセットに含まれる市の名前のみをidをキーにして返却（表においてわかりやすいように市の名前のみを返すことを想定）
    public static function get_only_city_name_from_ids($ids){
        return Address::whereIn("id",$ids)->pluck("city","id");
    }

    // 全住所を県=>市=>町の入れ子配列で返す
    public static function get_all_address_lists(){
        // 県と市でグループ分けしたリスト
       return Address::select("id","pref","city","town")->whereNot(
        function($data){
            // 世帯が0、でかつ水面などのデータもしくは空白は除く
            $data->where("household","=",0)->where(function($zero_household_data){
                // ?は外部入力のためのプレースホルダー
                $zero_household_data->whereRaw("town REGEXP ?",Statistics::INVALID_TOWN_REGEXP)
                ->orWhereRaw("Trim(town) = ''");
            });
        })->get()->groupBy(["pref","city"]);
    }

    // 県と市の名前が存在するかを判断
    public static function is_pref_and_city_sets_exists($pref,$city){
        return Address::where("pref",$pref)->where("city",$city)->exists();
    }


    // 県と市を設定し、そのすべての町の住所の[id=>住所]セットを返す
    public static function get_all_town_data_in_the_city($pref,$city){
        return Address::selectRaw("id,concat('city','town') as address_name")->where("pref",$pref)->where("city",$city)->pluck("address_name","id");
    }

    // 住所の配列のうち、存在しないものを返す
    public static function get_not_exists_address_name_in_array($array){
        return collect($array)->diff(Address::whereIn(DB::raw("concat(pref,city,town)"),$array)->selectRaw("concat(pref,city,town) as address_name")->pluck("address_name"));
    }

    // 町目のリストから、idリストを返す(町目がない時のエラーは除去済の前提)
    public static function get_id_lists_from_town_names($address_names){
        return Address::select("id")->whereIn(DB::raw("concat(pref,city,town)"),$address_names)->get();
    }




}
