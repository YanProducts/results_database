<?php

namespace App\Http\Requests\FieldStaff;

use Illuminate\Foundation\Http\FormRequest;

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
            //日付
            "date"=>["required","date"],
            // データ一式
            "reportData"=>["required","array"],
            // 町目やplan先が載ったassignのsqlのid //sqlに存在するか
            "reportData.*.assignId"=>["required",],
            // メイン案件の報告書の数字
            "reportData.*.mainCount"=>["required","integer"],
            //サブ案件

        ];
    }

    public function messages(){
        return[

        ];
    }
}
