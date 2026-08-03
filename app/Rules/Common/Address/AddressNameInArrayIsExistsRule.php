<?php

namespace App\Rules\Common\Address;

use App\Support\Common\ModelHelpers\AddressHelpers;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AddressNameInArrayIsExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //配列になっている住所(idではなく名前)が、すべて存在するかどうか(重複はひとまずOKとする//別箇所でメッセージなどで伝える)
        if(count($non_exist_sets_in_addresses=AddressHelpers::get_not_exists_address_name_in_array($value))>0){
            $fail("以下の住所は見つかりませんでした\n".$non_exist_sets_in_addresses->implode("\n"));
        }
    }
}
