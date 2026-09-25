<?php

namespace App\Rules\WholeData;

use App\Support\Auth\UserRoleResolver;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

// 投稿されたidは、投稿されたroleのリストに含まれるか
class IdInRoleRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function __construct(public string $role)
    {
    }


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 検証はUserRoleResolverから呼んでくる
        if(UserRoleResolver::check_id_exisits($value,$this->role)){
            $fail("職種と登録者が一致しません");
        }
    }
}
