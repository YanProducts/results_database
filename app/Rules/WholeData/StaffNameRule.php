<?php

namespace App\Rules\WholeData;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Utils\Regex;

// 日本語の文字列のみを許容
class StaffNameRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 空文字は許容する
        // 下記の正規表現を*に変えても良いが、敢えてパターンを仕分ける
        if($value==""){
            return;
        }

        // 文字がある場合は全角の文字列と数字のみ
          if(!Regex::check_jpn_words_only($value)){
            $fail("スタッフ名は漢字仮名全角数字のみでお願いします");
        }
    }
}
