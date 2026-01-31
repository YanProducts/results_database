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

  public function get_model_name(){
    return "App\\Models\\" .Str::studly($this->value)."List";
  }

}