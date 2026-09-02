<?php

namespace App\Support\Common\ValidationHelpers;

// 報告書作成における共通関数
class ReportValidationRules{
    public static function rules(){
        return[
            // "reportData"=>["required","array","min:1"],
            // "reportData.*.assignId"=>["required",new AssignExistsRule,new AssignedToAuthUserRule],
            // メイン案件の報告書の数字
            // "reportData.*.mainCount"=>["required","integer"],
            //サブ案件(この箱自体は必要だけど、単配の時は箱の中身は空でも良い)
            // "reportData.*.subData"=>["present","array"],
            // "reportData.*.subData.*.projectId"=>["nullable",new ProjectsExistsRule], //カスタムルールはプロジェクトIdが存在するか
            // "reportData.*.subData.*.subCount"=>["nullable","integer"],
        ];
    }
    public static function messages(){
        return [
            // "reportData.required"=>"記入が確認されませんでした",
            // "reportData.array"=>"データ形式の以上です",
            // "reportData.*.assignId.required"=>"各町目のデータが存在しません",
            // "reportData.*.mainCount.required"=>"各町目のデータが存在しません",
            // "reportData.*.mainCount.integer"=>"報告書の項目が数字ではありません",
            // "reportData.*.subData.present"=>"併配案件のデータが存在しません",
            // "reportData.*.subData.array"=>"併配案件のデータの異常です",
        ];
    }
}
