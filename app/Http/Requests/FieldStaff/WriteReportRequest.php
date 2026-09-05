<?php

namespace App\Http\Requests\FieldStaff;
use App\Support\Common\ValidationHelpers\ReportValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Common\AssignExistsRule;
use App\Rules\Common\AssignedToUserRule;
use Illuminate\Support\Facades\Auth;
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
            // 「現場担当、編集ではない」の条件で共通関数で検証
            ...ReportValidationRules::rules(Auth::user()->authable_id,false)
        ];
    }

    public function messages(){
        return[
            ...ReportValidationRules::messages()
        ];
    }
}
