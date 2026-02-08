<?php

namespace App\Http\Requests\Auth;

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
        if(str_contains($this->route()->getName(),"whole_data")){
            $rule["user_name"]=["required",new WholeDataAdministerExistsRule];
        }else{
            $rule["user_name"]=["required",new UserNameExistsRule];
        }

        // それぞれ入力されているか、ユーザー名が存在するか、ユーザー名はroleのユーザーか
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
