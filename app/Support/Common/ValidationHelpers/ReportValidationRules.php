<?php

namespace App\Support\Common\ValidationHelpers;
use App\Rules\Common\AssignExistsRule;
use App\Rules\Common\AssignedToUserRule;
use App\Rules\Common\ProjectsExistsRule;
use App\Rules\Common\ReportDateRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// 報告書作成における共通関数
class ReportValidationRules{

    public static function rules($staff_id,$is_edit){
        return[
            "date"=>["required","date",new ReportDateRule($is_edit)],
            "reportData"=>["required","array","min:1"],
            "reportData.*.assignId"=>["required",new AssignExistsRule,new AssignedToUserRule($staff_id)],
            // メイン案件の報告書の数字
            "reportData.*.mainCount"=>[$is_edit ? "nullable" : "required", "integer",],
            // サブ案件(この箱自体は必要だけど、単配の時は箱の中身は空でも良い)
            "reportData.*.subData"=>["present","array"],
            "reportData.*.subData.*.projectId"=>["nullable",new ProjectsExistsRule], //カスタムルールはプロジェクトIdが存在するか
            "reportData.*.subData.*.subCount"=>["nullable","integer"],
        ];
    }
    public static function messages(){
        return [
            "date.required"=>"日付が選択されていません",
            "date.date"=>"日付の形式が異常です",
            "reportData.required"=>"記入が確認されませんでした",
            "reportData.array"=>"データ形式の以上です",
            "reportData.*.assignId.required"=>"各町目のデータが存在しません",
            "reportData.*.mainCount.required"=>"各町目のデータが存在しません",
            "reportData.*.mainCount.integer"=>"報告書の項目が数字ではありません",
            "reportData.*.subData.present"=>"併配案件のデータが存在しません",
            "reportData.*.subData.array"=>"併配案件のデータの異常です",
        ];
    }
}
