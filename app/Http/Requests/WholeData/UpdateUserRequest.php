<?php

namespace App\Http\Requests\WholeData;

use App\Rules\WholeData\IdInRoleRule;
use App\Rules\WholeData\RoleExistsRule;
use App\Support\WholeData\UpdateUserValidation;
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
            ...UpdateUserValidation::added_rule_by_cases($this->all()),
            // 職種
            "role"=>["required",new RoleExistsRule],
            // id(それぞれのlistテーブルでのid、職種と連動)
            "id"=>["required","integer",new IdInRoleRule($this->input("role"))],
            // 退職休職か否か //現在と変更したら真 偽の場合は送信されないから必ずtrue
            "isInWorkChange"=>["sometimes","accepted"],
            // パスワードリセット //偽の場合は送信されないから必ずtrue
            "registered"=>["sometimes","accepted"]
        ];
    }
    public function messages(): array
    {
        return [
            "role.required"=>"職種が読み取れません",
            "id.required"=>"対象ユーザーが読み取れません",
            "id.integer"=>"対象ユーザーの取得値の異常です",
            "isInWorkChange.boolean"=>"稼働状況が不正な値です",
            "registered.boolean"=>"パスワードリセットが不正な値です",
            "placeId.integer"=>"営業所の値の異常です",
            "placeId.missing"=>"この職種に営業所は登録できません",
            "staffName.missing"=>"この職種にスタッフ名称は登録できません",
            "staffName.unique"=>"同じ営業所に同名のスタッフが存在します",
        ];
    }
}
