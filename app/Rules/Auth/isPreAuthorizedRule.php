<?php

namespace App\Rules\Auth;

use App\Support\Auth\UserRoleResolver;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

// Register前に統括者に事前に登録されているかどうか
class isPreAuthorizedRule implements ValidationRule
{
    // どこから来たかを定義
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
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //モデルの取得
        $model_name=UserRoleResolver::get_model_from_route($this->route_name);
        //登録されているかを調べる
        if(!$model_name::where("user_name",$value)->exists()){
            $fail("登録されていないユーザー名です");
        }
    }
}
