<?php

namespace App\Rules\Auth;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\WholeData;
use Illuminate\Support\Facades\Log;

class WholeDataAdministerNotExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // レコードが1件でも入っていたらアウト
        if(WholeData::exists()){
            $fail("統括者は既に登録されています");
        }
    }
}
