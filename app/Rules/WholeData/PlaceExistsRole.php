<?php

namespace App\Rules\WholeData;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Place;

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
        //営業所がSQLに存在するか

        // 敢えて選択しないのはOK
        if($value==""){
            return;
        }

        // 選択したにも関わらず登録されていない場合はアウト
        if(!Place::where("place_name",$value)->exists()){
            $fail("登録されていない営業所です");
        }
    }
}
