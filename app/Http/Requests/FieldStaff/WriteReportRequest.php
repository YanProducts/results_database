<?php

namespace App\Http\Requests\FieldStaff;

use App\Rules\Common\AssignExistsRule;
use App\Rules\Common\ProjectsExistsRule;
use App\Rules\FieldStaff\AssignedToAuthUserRule;
use App\Rules\FieldStaff\ReportDateRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

// 報告書のバリデーション
class WriteReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //日付(カスタムルールは表示期限内かどうかの確認)
            "date"=>["required","date", new ReportDateRule],
            // データ一式
            "reportData"=>["required","array","min:1"],
            // 町目やplan先が載ったassignのsqlのid //カスタムルールはsqlに存在するか,そのユーザーに割り当てられたものか
            "reportData.*.assignId"=>["required",new AssignExistsRule,new AssignedToAuthUserRule],
            // メイン案件の報告書の数字
            "reportData.*.mainCount"=>["required","integer"],
            //サブ案件(この箱自体は必要だけど、単配の時は箱の中身は空でも良い)
            "reportData.*.subData"=>["present","array"],
            "reportData.*.subData.*.projectId"=>["nullable",new ProjectsExistsRule], //カスタムルールはプロジェクトIdが存在するか
            "reportData.*.subData.*.subCount"=>["nullable","integer"],
        ];
    }

    public function messages(){
        return[
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
