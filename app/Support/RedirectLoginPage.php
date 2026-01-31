<?php
// Auth認証がないor違う認証でログインしている場合、目的のログインページへと向かわせるメソッド
namespace App\Support;
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
    return redirect()->route("view_error_route");

    // return match(true){
    //     str_contains($path,"field")=> redirect()->route("field_staff.login"),
    //     str_contains($path,"clerical")=>redirect()->route("clerical.login"),
    //     str_contains($path,"branch")=>redirect()->route("branch_manager.login"),
    //     str_contains($path,"project")=>redirect()->route("project_operator.login"),
    //     default=>redirect()->route("view_error_route")
    // };
  }
}