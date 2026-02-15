<?php

namespace App\Http\Requests\WholeData;

use App\Rules\WholeData\PlaceNameRule;
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
        return [
            "places"=>["required",new PlaceNameRule],
            "colors.*"=>["required","numeric","min:0","max:255"],

        ];
    }
    public function messages(): array
    {
        return [

        ];
    }
}
