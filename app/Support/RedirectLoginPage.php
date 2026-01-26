<?php
// Auth認証がないor違う認証でログインしている場合、目的のログインページへと向かわせるメソッド
namespace App\Support;
class RedirectLoginPage{
  public static function RedirectLoginPageFunc($request){
    $path=$request->route()?->getName();

    return match(true){
        str_contains($path,"field")=> redirect()->route("field_staff.login"),
        str_contains($path,"clerical")=>redirect()->route("clerical.login"),
        str_contains($path,"branch")=>redirect()->route("branch_manager.login"),
        default=>redirect()->route("view_error_route")
    };
  }
}