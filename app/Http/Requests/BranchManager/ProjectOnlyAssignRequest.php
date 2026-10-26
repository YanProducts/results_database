<?php

namespace App\Http\Requests\BranchManager;

use App\Rules\BranchManager\AssignDateRule;
use App\Rules\BranchManager\StaffIsWorkingRule;
use App\Rules\Common\ProjectPalnIdExistsRule;
use App\Rules\Common\StaffIsExistsRule;
use Illuminate\Foundation\Http\FormRequest;

// 案件のみの
class ProjectOnlyAssignRequest extends FormRequest
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
            "assignDate"=>["required","date_format:Y-m-d",new AssignDateRule],
            "allData"=>["required","array"],
            //スタッフのIdが存在するか、その営業所にいるか
            "allData.*.staffId"=>["required","integer",new StaffIsExistsRule,new StaffIsWorkingRule],
            "allData.*.projectPlanIds"=>["present","array"],
            // 案件のId(projectPlannedCountsのid)が存在するか
            "allData.*.projectPlanIds.*"=>["integer",new ProjectPalnIdExistsRule] //これはrequiredになるのか！？
        ];
    }

    public function messages(){
        return[
            "assignDate.required"=>"日付が取得できません",
            "assignDate.date_format"=>"日付取得のエラーです",
            "allData.required"=>"データ選択されていません",
            "allData.array"=>"データの形が異常です",
            "allData.*.staffId.required"=>"スタッフ指定のエラーです",
            "allData.*.staffId.integer"=>"スタッフ指定のエラーです",
            "allData.*.projectPlanIds.present"=>"案件指定のエラーです",
            "allData.*.projectPlanIds.array"=>"案件指定のエラーです",
            "allData.*.projectPlanIds.*.integer"=>"案件取得のエラーです",
        ];
    }
}
