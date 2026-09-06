<?php

namespace App\Http\Requests\BranchManager;

use App\Rules\Common\ReportDateRule;
use Illuminate\Foundation\Http\FormRequest;

class ChoiceFromDateRequest extends FormRequest
{
    // 報告書の編集を日付側から行うバリデーション

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
         "date"=>["required","date_format:Y-m-d",new ReportDateRule(true)],
        ];
    }
    public function messages(){
        return[
            "date.required"=>"日付が取得できません",
            "date.date_format"=>"日付の形式の異常です",
        ];
    }
}
