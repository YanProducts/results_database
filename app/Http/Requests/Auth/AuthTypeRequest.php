<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserRole;
use App\Support\Auth\AuthTypeChack;

class AuthTypeRequest extends FormRequest
{
    //新規登録やログインに向かうページが規定のものかどうかを判断

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // ルート名が認証に向かうものとして正しいか
        if(!AuthTypeChack::is_valid_auth_rule($this->route()->getName())){
            //return falseだとInertiaで検知されない
            abort(404);
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // パラメータは存在しないので何も設定しない
        return [
        ];
    }
    public function messages(): array
    {
        return [
            //
        ];
    }
}
