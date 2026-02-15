<?php

namespace App\Rules\Common;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MustBeEmptyRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    // 例：roleの値によって値が入っていては行けない項目など
    // Inertiaのformの初期値は空文字

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //空文字のみOK
        if($value!==""){
            $fail("不明な値が入力されています");
        }
    }
}
