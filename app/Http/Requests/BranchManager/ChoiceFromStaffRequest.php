<?php

namespace App\Http\Requests\BranchManager;

use App\Rules\Common\StaffIsExistsRule;
use Illuminate\Foundation\Http\FormRequest;

// 過去の報告書の確認と編集をスタッフから変更するとき
class ChoiceFromStaffRequest extends FormRequest
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
         "staffs"=>["required","array"],
         "staffs.*"=>["integer",new StaffIsExistsRule]
        ];
    }
    public function messages(): array
    {
        return [
            "staffs.required"=>"必要なデータが送信できませんでした",
            "staffs.array"=>"データ形式の異常です",
            "staffs.*.integer"=>"データ形式の異常です",
        ];
    }
}
