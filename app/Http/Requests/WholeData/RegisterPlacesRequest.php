<?php

namespace App\Http\Requests\WholeData;

use App\Rules\WholeData\PlaceNameRule;
use App\Rules\WholeData\PlaceNotExistsRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterPlacesRequest extends FormRequest
{
    // 営業所登録のバリデーション
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // 場所名は文字列で必須、RGBはnullableだがあれば数字で
        // Laravel11では全角スペースもtrimしてくれる
        return [
            // 場所名は日本語で、すでに登録されていないかをチェック
            "place"=>["required",new PlaceNameRule, new PlaceNotExistsRule],
            "colors.*"=>["required","numeric","min:0","max:255"],

        ];
    }
    public function messages(): array
    {
        return [
            "place.required"=>"場所名は必須です",
            "colors.*.required"=>"入力できていない色があります",
            "colors.*.numeric"=>"色の値が異常です",
            "colors.*.max"=>"色の値が異常です",
            "colors.*.min"=>"色の値が異常です",
        ];
    }
}
