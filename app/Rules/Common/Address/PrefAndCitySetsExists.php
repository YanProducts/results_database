<?php

namespace App\Rules\Common\Address;

use App\Support\Common\ModelHelpers\AddressHelpers;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PrefAndCitySetsExists implements ValidationRule
{
    public function __construct(public string $pref)
    {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //県と市のセットが住所録にあるか
        if(!AddressHelpers::is_pref_and_city_sets_exists($pref=$this->pref,$value)){
            $fail($pref."県".$value."市は見つかりませんでした");
        }
    }
}
