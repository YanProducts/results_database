<?php

namespace App\Rules\WholeData;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Utils\Regex;
use Illuminate\Support\Facades\Log;

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
        if(!Regex::check_jpn_words_only($value)){
            $fail("営業所名は漢字仮名全角数字のみでお願いします");
        }
    }
}
