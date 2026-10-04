<?php

namespace App\Http\Requests\BranchManager;

use App\Rules\Common\StaffIsExistsRule;
use App\Rules\Common\AssignExistsRule;
use App\Rules\Common\AssignedToUserRule;
use App\Support\Common\ValidationHelpers\ReportValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

// 報告書完成した際におけるバリデーション
class CompleteEditReportRequest extends FormRequest
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
        $staff_rule=["required","integer",new StaffIsExistsRule];
        if(str_contains($this->route()->getName(),"field_staff")){
            $staff_rule[]=Rule::in([Auth::user()->authable_id]); //配列内の値のどれか＝1つなら必ずその値
        }

        return [
            // 4などidの形式
            "staff"=>$staff_rule,
            // 「営業所担当、編集」の条件で共通関数で検証
            ...ReportValidationRules::rules($this->input("staff"),true)
        ];
    }

    public function messages()
    {
        return [
            ...ReportValidationRules::messages()
        ];
    }
}
