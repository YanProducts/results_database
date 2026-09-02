<?php

namespace App\Http\Requests\BranchManager;

use App\Support\Common\ValidationHelpers\ReportValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Override;

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
        return [
            "date"=>["requred","date",],
            // 4などidの形式
            "staff"=>[],
            ...ReportValidationRules::rules()

        ];
    }

    public function messages()
    {
        return [

            ...ReportValidationRules::messages()
        ];
    }
}
