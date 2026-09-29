<?php

namespace App\Http\Requests\WholeData;

use App\Rules\WholeData\PlaceExistsRule;
use App\Rules\WholeData\PlaceNameRule;
use App\Rules\WholeData\PlaceNotExistsRule;
use Illuminate\Foundation\Http\FormRequest;

// 営業所の変数におけるバリデーション
class UpdatePlaceRequest extends FormRequest
{
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
        return [
            "id"=>["required","integer",new PlaceExistsRule],
            "place_name"=>["required",new PlaceNameRule,new PlaceNotExistsRule],
            "is_active"=>["sometimes","boolean"],//フロント側の変更確認でisInWorkChangeが真なら元と変更、偽なら変更しない。その上で変更しない場合はキーにセットしない。Userの場合とは構造が別なので、acceptedではなくboolean
            "red"=>["sometimes","integer","between:0,255"],
            "green"=>["sometimes","integer","between:0,255"],
            "blue"=>["sometimes","integer","between:0,255"],
        ];
    }

    public function messages(): array
    {
        return [
            "id.required" => "営業所が取得できません",
            "id.integer" => "営業所データが不正です",
            "place_name.required" => "営業所名を入力してください",
            "is_active.boolean" => "稼働状況が不正です",
            "red.integer" => "赤の値は整数で入力してください",
            "red.between" => "赤の値は0〜255で入力してください",
            "green.integer" => "緑の値は整数で入力してください",
            "green.between" => "緑の値は0〜255で入力してください",
            "blue.integer" => "青の値は整数で入力してください",
            "blue.between" => "青の値は0〜255で入力してください",
        ];
    }
}
