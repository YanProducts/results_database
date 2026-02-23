<?php

namespace App\Rules\Auth;

use Closure;
use App\Models\UserAuth;
use Illuminate\Contracts\Validation\ValidationRule;

class UserNameExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // ログイン時に登録されているかどうか
        // routeにwhole_dataと入っているときはwhole_dataに登録されているかで、別途既に弾いている
        //上記以外の場合は、Authに登録されているかどうか
        if(!UserAuth::where("authable_id",$value)->exists()){
            $fail("まだ登録されていません");
        }


    }
}
