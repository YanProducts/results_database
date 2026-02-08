<?php
// ユーザーの種類(ログインなどに使う)
// phpのEnumで定義(casesという関数で全てのEnumをEnumとして配列化。valueでその値を取り出し、keyでそのキーを取り出す)
namespace App\Enums;
use Illuminate\Support\Str;

enum UserRole : string{
  case FieldStaff ="field_staff";
  case Clerical="clerical";
  case ProjectOperator="project_operator";
  case BranchManager="branch_manager";

  //そのインスタンスのvalueに応じたモデルの名前を返す
  public function get_model_name(){
    return "App\\Models\\" .Str::studly($this->value)."List";
  }

// 日本語の文字列を返す(値から)
public static function get_jpn_description($value){
    return match($value){
        "field_staff"=>"現場担当",
        "clerical"=>"入力担当",
        "project_operator"=>"案件担当",
        "branch_manager"=>"営業所長",
        // whole_dataは特別なので以下で設定済み
        default=>"不明"
    };
}

// どのauthページかを返す
public static function get_auth_page_type($request){

    // ルートの名前
    $route_name=$request->route()->getName();

    //全般データなら、whole_dataを返す
    if(str_contains($route_name,"whole_data")){
        return
        [
            "prefix"=>"whole_data",
            "what"=>"全体統括"
        ];
    }

    // それ以外ならそのぺーじを返す
    foreach(self::cases() as $role){
        if(str_contains($route_name,$role->value)){
        $value=$role->value;
        $jpn_word=self::get_jpn_description($value);
        // 日本語=>英語の配列で返す
         return [
            "prefix"=>$value,
            "what"=>$jpn_word
         ];
        }
    }

    // 上記以外の場合、ページが見つからない例外を投げる
    // 定義のところでも拾ってくれる
    abort(404);

}


}
