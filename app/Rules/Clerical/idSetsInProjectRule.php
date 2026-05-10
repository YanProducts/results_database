<?php

namespace App\Rules\Clerical;

use App\Models\Project;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IdSetsInProjectRule implements ValidationRule
{
    // 配列で渡されたidセットが全てprojectsのテーブルに存在するか
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!collect($value)->some(fn($each_id)=>Project::where("id",$each_id)->exists())){
            $fail("存在しない案件が選択されています\nリロードするか、作成者に連絡してください");
        }
    }
}
