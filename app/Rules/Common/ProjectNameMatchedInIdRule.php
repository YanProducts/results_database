<?php

namespace App\Rules\Common;

use App\Support\Common\ModelHelpers\ProjectHelpers;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

// プロジェクト名がidと投稿された連携しているか(間違いの確認に近い)
class ProjectNameMatchedInIdRule implements ValidationRule
{
    protected int $id;
    public function __construct($id)
    {
        $this->id=$id;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(ProjectHelpers::get_project_name_from_id($this->id)!==$value){
            $fail("案件名取得におけるエラーです");
        }
    }
}
