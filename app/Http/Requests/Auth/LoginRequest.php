<?php

namespace App\Http\Requests\Auth;

use App\Rules\Auth\isPreAuthorizedRule;
use App\Rules\Auth\UserNameExistsRule;
use App\Rules\Auth\WholeDataAdministerExistsRule;
use Illuminate\Foundation\Http\FormRequest;

// ログインのリクエスト
class LoginRequest extends FormRequest
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
        $rule=[];
        $route=$this->route()->getName();
        if(str_contains($route,"whole_data")){
            // 統括ユーザーが存在するか
            $rule["userName"]=["required",new WholeDataAdministerExistsRule];
        }else{
            // そのrouteのroleにユーザーは存在するか
            // bailをつけるとエラーが生じたところで止まり、次のエラーは検証されない(usernameexists内でisPreAuthroizedを再検証せずに済む)
            $rule["userName"]=["bail","required",new isPreAuthorizedRule($route),new UserNameExistsRule($route)];
        }

        return [
            ...$rule,
            "passWord"=>["required"]
        ];
    }
        public function messages(){
        return[
            "userName.required"=>"ユーザーネームを入力してください",
            "passWord.required"=>"パスワードを入力してください",
         ];
        }
}
