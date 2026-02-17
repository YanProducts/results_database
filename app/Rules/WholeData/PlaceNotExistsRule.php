<?php

namespace App\Rules\WholeData;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Place;

class PlaceNotExistsRule implements ValidationRule
{
    //営業所作成の時に、その営業所が登録されている名前と同じだったらアウト

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        // 登録されていたら場合はアウト
        if(Place::where("place_name",$value)->exists()){
            $fail("概に登録されています");
        }

    }
}
