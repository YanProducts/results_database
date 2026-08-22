<?php

namespace App\Http\Requests\BranchManager;

use App\Rules\Common\StaffIsExistsRule;
use Illuminate\Foundation\Http\FormRequest;

// 編集する報告書の決定
class ReportChoiceDecideRequest extends FormRequest
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
            "date"=>["required","date_format:Y-m-d"],
            "staffId"=>["required","integer",new StaffIsExistsRule]
        ];
    }
    public function messages(): array
    {
        return [
            "date.required"=>"日付が取得できません",
            "date.date_format"=>"日付の形式の異常です",
            "staffId.required"=>"スタッフ取得時のエラーです",
            "staffId.integer"=>"スタッフの形式の異常です",
        ];
    }
}
