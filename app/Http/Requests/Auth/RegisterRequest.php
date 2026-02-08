<?php

namespace App\Http\Requests\Auth;

use App\Rules\Auth\PassWordRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Support\Auth\AuthTypeChack;
use App\Rules\Auth\isPreAuthorizedRule;
use App\Rules\Auth\WholeDataAdministerNotExistsRule;
use Illuminate\Support\Facades\Log;

class RegisterRequest extends FormRequest
{
    // 認証における投稿のパラメータが正しいか

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        // ルート名の取得
        $route=$this->route()->getName();

        // ルート名が認証に向かうものとして正しいか
        if(!AuthTypeChack::is_valid_auth_rule($route)){
            //return falseだとInertiaで検知されない
            abort(403);
            // return false;
        }
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules=[];

        // whole_dataの時はemailが必要。他は必要ない
        // whole_dataのexistsRuleは本来はauthorityで除去すべきだが、Inertiaにmessageが返されないため、ここで設定
        if(str_contains($this->route()->getName(),"whole_data")){
            $rules["userName"]=["required","min:2", new WholeDataAdministerNotExistsRule];
            $rules["email"]=["required","email"];
        }else{
            // ユーザー名がauthで重ならないようにするのは統括者が登録の際に行うこと
            $rules["userName"]=["required",new isPreAuthorizedRule];
        }

        return [
            ...$rules,
            "passWord"=>["required","min:8","max:30","confirmed",new PassWordRule],
        ];
    }

    public function messages(){
        return[
            "userName.required"=>"ユーザーネームを入力してください",
            "userName.min"=>"ユーザーネームは2文字以上です",
            "passWord.required"=>"パスワードを入力してください",
            "passWord.min"=>"パスワードは８文字以上でお願いします",
            "passWord.max"=>"パスワードは３０文字以内でお願いします",
            "email.required"=>"メールアドレスを入力してください",
            "email.email"=>"メールアドレスの形式が違います",
            "passWord.confirmed"=>"パスワードが一致しません",
        ];
    }
}
