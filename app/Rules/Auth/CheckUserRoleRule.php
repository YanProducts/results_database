<?php

namespace App\Rules\Auth;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\UserAuth;

class CheckUserRoleRule implements ValidationRule
{

    // routeNameを引数で取ってきて、インスタンス作成時に定義
    public string $route_name;
    public function __construct(string $route_name)
    {
        $this->route_name = $route_name;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    // ユーザー名があるroleがルートのroleとあっているかを調べる(存在有無は別のruleで定義)
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {



        // ユーザーネームのあるmodelのインスタンスを取ってくる
        $model_instance=UserAuth::where("authable_id",$value)->first();





        // roleの取得(UserAuthで定義)
        $role=$model_instance->role();

        //ルート名にroleが含まれているか
        if(!str_contains($this->route_name,$role)){
            $fail("該当ページでのユーザー名が見つかりません");
        }

    }
}
