<?php

namespace App\Rules\Auth;

use App\Utils\Regex;
use Closure;
use Dotenv\Repository\Adapter\ReplacingWriter;
use Illuminate\Contracts\Validation\ValidationRule;

class PassWordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        // 使用不可の文字が１文字でも合った場合はアウト
        $preg_forbitten="/[^(0-9A-Za-z!@#$%^&*()\-_=+\[\]{}:;,.?\/)]/";

        //パスワードに小文字大文字数字が全て含まれているか
        if(!Regex::check_password_rule($value)){
            $fail("パスワードは大文字・小文字・数字を\n全て含んでください");
        }

        // 許可された以外の文字が投稿されていたとき
        if(Regex::check_password_forbitten_rule($value)){
            $fail("パスワードは半角英数字と\n!@#$%^&*()\-_=+\[\]{}:;,.?/以外は使えません");
        }

    }
}
