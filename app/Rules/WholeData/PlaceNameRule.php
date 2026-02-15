<?php

namespace App\Rules\WholeData;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

// 営業所名
class PlaceNameRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 全角の文字列と数字のみ
        if(!preg_match("/^[\p{Hiragana}\p{Katakana}\p{Han}]+$/u",$value)){
            $fail("スタッフ名は漢字仮名全角数字のみでお願いします");
        }
    }
}
