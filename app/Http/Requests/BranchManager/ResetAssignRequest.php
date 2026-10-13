<?php

namespace App\Http\Requests\BranchManager;

use App\Rules\BranchManager\AssignDateRule;
use Illuminate\Foundation\Http\FormRequest;

// 案件の割り当てをリセットするためのリクエス
class ResetAssignRequest extends FormRequest
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
            "date"=>["required","date",new AssignDateRule]
        ];
    }
    public function messages(): array
    {
        return [
            "date.required"=>"日付を読み取れません",
            "date.date"=>"日付の形式の異常です"
        ];
    }
}
