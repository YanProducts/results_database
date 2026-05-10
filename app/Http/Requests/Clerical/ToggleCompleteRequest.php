<?php

namespace App\Http\Requests\Clerical;

use App\Rules\Common\ProjectNameMatchedInIdRule;
use App\Rules\Common\ProjectsExistsRule;
use Illuminate\Foundation\Http\FormRequest;

// 案件を完成もしくは編集可能にするrequest
// fetchで返す

class ToggleCompleteRequest extends FormRequest
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
            // 案件id
            "projectId"=>["required",new ProjectsExistsRule],
            // 案件名
            "projectName"=>["required",new ProjectNameMatchedInIdRule($this->input("projectId"))],
        ];
    }

    public function messages(): array
    {
        return [
            "projectName.required"=>"案件選択でエラーが生じました",
            "projectId.required"=>"案件選択でエラーが生じました"
        ];
    }
}
