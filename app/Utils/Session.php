<?php
namespace App\Utils;

// sessionの管理
class Session{
// sessionの作成(key=>valueの配列で渡すこと)
 public static function create_sessions($key_value_sets){
    foreach($key_value_sets as $key=>$value){
        session([$key=>$value]);
    }
 }



 // flushsessionの作成(key=>valueの配列で渡すこと)
 public static function create_flush_sessions($key_value_sets){
    foreach($key_value_sets as $key=>$value){
        session()->flash($key,$value);
    }
 }
    

// sessionの削除(keyの配列で渡すこと)
 public static function delete_sessions($keys){
    foreach($keys as $key){
        session()->forget($key);
    }
 }


}
