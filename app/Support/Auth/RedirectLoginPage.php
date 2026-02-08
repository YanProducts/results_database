<?php
// Auth認証がないor違う認証でログインしている場合、目的のログインページへと向かわせるメソッド
namespace App\Support\Auth;
use App\Enums\UserRole;

class RedirectLoginPage{
  public static function RedirectLoginPageFunc($request){
    // パスの取得
    $path=$request->route()?->getName();

    // roleのEnum(PHP8)を取得,ルート名がそのroleから始まっているかで評価
    foreach(UserRole::cases() as $case){
      if(str_starts_with($path,$case->value)){
        return redirect()->route($case->value.".login");
      }
    }
    return redirect()->route("view_error");
  }
}
