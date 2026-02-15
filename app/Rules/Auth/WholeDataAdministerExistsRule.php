<?php

namespace App\Rules\Auth;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\WholeData;
use Illuminate\Support\Facades\Log;

class WholeDataAdministerExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 登録されていなければアウト
        // ユーザー名が違えばアウト
        if(!WholeData::where("user_name",$value)->exists()){
            $fail("該当ユーザーは存在しません");
        }
    }
}
