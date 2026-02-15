<?php

namespace App\Rules\WholeData;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UserNameRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    // ユーザー名に関するバリデーション
    // 存在と長さに関する指定はvalidationで行う
    // それ以外に指定があるものは個別設定する
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 現時点では半角英数字のみとする
        if(!preg_match("/^[A-Za-z0-9]+$/",$value)){
            $fail("ユーザー名は半角英数字のみでお願いします");
        }
    }
}
