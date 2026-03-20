<?php

// プロジェクトが存在するかどうか
namespace App\Rules\ProjectOperator;

use App\Models\Project;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProjectsExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //プロジェクトが存在しなければfail
        if(!Project::where("id",$value)->exists()){
            $fail("プロジェクトが存在しません");
        }
    }
}
