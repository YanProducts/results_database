<?php

// スタッフの割り当てに関するリクエス
namespace App\Http\Requests\BranchManager;

use App\Rules\BranchManager\AssignDateRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\BranchManager\StaffIsWorkingRule;
use App\Rules\BranchManager\PlanIdIsExistsRule;
use App\Rules\BranchManager\PlanValidateForDateRule;
use App\Rules\BranchManager\StaffIsExistsRule;

class AssignStaffRequest extends FormRequest
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
            // 期間内の日付か
            "assignDate"=>["required","date_format:Y-m-d",new AssignDateRule],
            "allData"=>["required","array"],
            // そのスタッフが存在するか、その日に出席しているスタッフか
            "allData.*.staffId"=>["required",new StaffIsExistsRule,new StaffIsWorkingRule],
            // planIdsは配列で渡る
            "allData.*.planIds"=>["required","array"],
            // 選ばれたplanIdが存在するか, planIdは投稿された日の中に入っているか
            "allDate.*.planIds.*"=>[new PlanIdIsExistsRule,new PlanValidateForDateRule($this->input("assignDate"))]

            // その日の出席スタッフが網羅されているかは、スタッフの一部のみが利用することも踏まえ、js側で確認を出すようにする

        ];
    }
    public function message(): array
    {
        return [
            "allData.required"=>"地図もしくは町目が選択されていません",
            "allData.array"=>"データの形が異常です",
            "allData.*.staffId.required"=>"データの形が異常です",
            "allData.*.planId.required"=>"データの形が異常です",
        ];
    }
}
