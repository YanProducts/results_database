<?php
// Auth認証がないor違う認証でログインしている場合、目的のログインページへと向かわせるメソッド
namespace App\Support\Auth;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class RedirectLoginPage{
  //  前に行こうとしていたページを保存してリダイレクト(whole_data以外)
  public static function redirect_login_page_func($request){

    // パスの取得
    $path=$request->route()?->getName();

    // roleのEnum(PHP8)を取得,ルート名がそのroleから始まっているかで評価
    foreach(UserRole::cases() as $case){
      if(str_contains($path,$case->value)){

        // 行こうとしたrouteをintendedに保存し、リダイレクト(middlewareに一旦式としてredirect)
        return Redirect::guest(route($case->value.".login"));
      }
    }
    return redirect()->route("view_error");
  }
}
