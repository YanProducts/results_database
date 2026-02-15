<?php

namespace App\Http\Requests\WholeData;

use App\Rules\Common\MustBeEmptyRule;
use App\Rules\WholeData\StaffNameRule;
use App\Rules\WholeData\UserNameRule;
use Illuminate\Foundation\Http\FormRequest;

class ProvisionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    //事前登録に関するバリデーション

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
        //個別ルール
        $rules=[];

        // 営業所名(営業所長と現場作業員には必要)
        // SQLに登録されている営業所銘菓を調べる
       if(in_array($this->input("role"),["field_staff","branch_manager"])){
           $rules["place"]=[];
       }else{
        // それ以外だと、営業所名は存在しては行けない
           $rules["place"]=[new MustBeEmptyRule];
       }
        // スタッフ名(現場作業員はnullableだが存在する場合は全角の文字のみ)
        if($this->input("role")=="field_staff"){
            // 現場作業員のみスタッフ名が必要（空文字許容）
            $rules["staff_name"]=[new StaffNameRule];
        }else{
            $rules["staff_name"]=[new MustBeEmptyRule];
        }


        //個別ルールを展開し、全体ルールを足す
        return [
            ...$rules,
            // role
            "role"=>["required", ],
            // ユーザー名
            "userName"=>["required","min:2","max:50",new UserNameRule],

        ];
    }
    public function messages(): array
    {
        return [
            //
        ];
    }
}
