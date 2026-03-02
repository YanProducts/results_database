<?php

namespace App\Http\Requests\ProjectOperator;

use App\Rules\WholeData\PlaceExistsRule;
use Illuminate\Foundation\Http\FormRequest;

// 案件割り当てのバリデーション
class DispatchRequest extends FormRequest
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
            // 開始日と終了日はcsvファイルから取得
            //開始日
            // "startDate"=>["required","date_format:Y-m-d"],
            // //終了日
            // "endDate"=>["required","date_format:Y-m-d"],
            // 営業所
            "place"=>["required",new PlaceExistsRule],
            // ファイルの大元
            "fileSets" => ["required", "array"],
            // 各ファイル(別途、ruleで例外除去)
            "fileSets.*" => ["file", "mimes:csv,xlsx", "max:2048"],
        ];
    }
    public function messages(){
        return[
            // "startDate.required"=>"開始日を選択してください",
            // "startDate.datetime"=>"開始日の書式エラーです",
            // "endDate.required"=>"終了日を選択してください",
            // "endDate.required"=>"終了日を選択してください",
        ];
    }
}
