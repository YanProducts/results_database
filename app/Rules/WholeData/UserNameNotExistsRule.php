<?php

namespace App\Rules\WholeData;

use App\Support\Auth\UserRoleResolver;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

// 統括者による事前登録の際に、ユーザー名がすでに作成されているものなら除外
// そのロールのもので行う
class UserNameNotExistsRule implements ValidationRule
{
    // role
    public string $role;
    public function __construct($role){
        $this->role=$role;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // roleからモデルを取得(route名から取得になっているが、str_containsなので、直接roleからの呼び出しも可能)
        $model_instance=UserRoleResolver::get_model_from_route($this->role);

        // そのモデルにすでにユーザー名が含まれていたらアウト
        $fail($model_instance::where("user_name",$value)->exists());

    }
}
