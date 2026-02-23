<?php

namespace App\Rules\WholeData;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Place;
use Illuminate\Support\Facades\Log;

// 営業所がSQLに存在するかのルール(スタッフ登録の際に使用)
class PlaceExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //営業所がSQLに存在するか(登録されていない場合はアウト)
        if(!Place::where("id",$value)->exists()){
            $fail("登録されていない営業所です");
        }
    }
}
