<?php

namespace App\Rules\WholeData;

use App\Support\Auth\UserRoleResolver;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

// 登録時にroleが存在するかのrole
class RoleExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 存在していないroleならfalse
        $fail(!UserRoleResolver::role_name_check($value));
    }
}
