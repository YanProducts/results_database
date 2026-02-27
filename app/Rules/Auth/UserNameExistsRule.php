<?php

namespace App\Rules\Auth;

use App\Actions\Auth\Login;
use Closure;
use App\Models\UserAuth;
use App\Support\Auth\UserRoleResolver;
use Illuminate\Contracts\Validation\ValidationRule;

class UserNameExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
    *
    * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
    */
    protected string $route_name;
    public function __construct($route){
        $this->route_name=$route;
    }


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // ログイン時に登録されているかどうか
        // routeにwhole_dataと入っているときはwhole_dataに登録されているかで、別途既に弾いている

        //上記以外の場合は、Authに登録されているかどうか

        // routeからroleを取得し、そのモデル名を取得
        $model_namespace=UserRoleResolver::get_model_from_route($this->route_name);

        // roleに登録されているユーザーid(事前登録は検証済)
        $id=Login::get_id_from_auth_data($model_namespace,$value);

        // ユーザー自身が新規登録を行なったか
        if(!UserAuth::where("authable_id",$id)->exists()){
            $fail("まだ登録されておりません\n新規登録から設定してください");
        }

    }
}
