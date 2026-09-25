<?php

namespace App\Http\Requests\WholeData;

use App\Rules\WholeData\IdInRoleRule;
use App\Rules\WholeData\RoleExistsRule;
use Illuminate\Foundation\Http\FormRequest;

// ユーザーの情報を編集した時のバリデーション
class UpdateUserRequest extends FormRequest
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
            // 職種
            "role"=>["required",new RoleExistsRule],
            // id(それぞれのlistテーブルでのid、職種と連動)
            "id"=>["required","integer",new IdInRoleRule($this->input("role"))],
            // 退職休職か否か
            "isInWorkChange"=>["nullable","boolean"],
            //スタッフ名
            "staffName"=>[],
            // 営業所名
            "selectedPlace"=>[],
            // パスワードリセット //偽の場合は送信されないから必ずtrue
            "registered"=>["nullable","boolean"]


        ];
    }
    public function messages(): array
    {
        return [
            //
        ];
    }
}
