<?php

namespace App\Rules\Common;

use App\Models\Address;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

// 住所のIdが存在するかどうか
class AddressExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!Address::where("id",$value)->exists()){
            $fail("存在しない住所が入っています");
        }
    }
}
