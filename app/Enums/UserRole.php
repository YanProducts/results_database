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
            self::FieldStaff=>"現場担当",
            self::Clerical=>"入力担当",
            self::ProjectOperator=>"案件担当",
            self::BranchManager=>"営業所長",
            // whole_dataは特別なので以下で設定済み
            default=>"不明"
        };
    }


    //  トップページのルート名を返す
    public static function top_page_route_name($curerent_route_name){
        // トップページの文字列を返す(将来的にパターンが増えたことを考慮して、現時点ではtop_pageが多いがベタ打ちにする)
        return match(true){
            str_contains($curerent_route_name,self::FieldStaff->value)=>"write_report",
            str_contains($curerent_route_name,self::Clerical->value)=>"top_page",
            str_contains($curerent_route_name,self::ProjectOperator->value)=>"action_select",
            str_contains($curerent_route_name,self::BranchManager->value)=>"top_page",
        };
    }

}
