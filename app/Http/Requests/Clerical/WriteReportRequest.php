<?php

namespace App\Http\Requests\Clerical;

use App\Rules\Clerical\PlanIdInProjectsRule;
use App\Rules\Common\ProjectsExistsRule;
use Illuminate\Foundation\Http\FormRequest;

class WriteReportRequest extends FormRequest
{
    // 入力担当が報告書を案件ベースに記入する時のバリデーション

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
            "projectId"=>["required","integer",new ProjectsExistsRule],
            "changedDifference"=>["required","array"],
            "changedDifference.*.planId"=>["required","integer","distinct","exists:distribution_plans,id",new PlanIdInProjectsRule($this->input("projectId"))],//distinctは重複禁止
            "changedDifference.*.countDifference"=>["required","integer","between:-100000,100000",],
        ];
    }

    public function messages(): array
    {
        return [
            "projectId.required"=>"案件が取得できませんでした",
            "projectId.integer"=>"案件名が予期せぬ値です",
            "changedDifference.required"=>"入力値が取得できませんでした",
            "changedDifference.array"=>"入力値が予期せぬ値です",
            "changedDifference.*.planId.required"=>"配布予定が取得できませんでした",
            "changedDifference.*.planId.integer"=>"配布予定が予期せぬ形状です",
            "changedDifference.*.planId.distinct"=>"配布予定町目が重複しています",
            "changedDifference.*.planId.exists"=>"配布予定が存在しません",
            "changedDifference.*.countDifference.required"=>"配布数が取得できませんでした",
            "changedDifference.*.countDifference.integer"=>"配布数が予期せぬ値です",
            "changedDifference.*.countDifference.between"=>"配布数が上限を超えています",
        ];
    }
}
