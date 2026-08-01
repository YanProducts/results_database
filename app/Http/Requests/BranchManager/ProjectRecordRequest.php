<?php

namespace App\Http\Requests\BranchManager;

use App\Exceptions\BusinessException;
use App\Rules\Common\AddressExistsRule;
use App\Rules\Common\StaffIsExistsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

// 営業所長が過去のデータを確認する際のバリデーション
class ProjectRecordRequest extends FormRequest
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

        // patternの値によって、その後に何が必要かを分ける
        $rules_by_pattern=match($this->input("pattern")){
            // 入力がリスト形式のとき
           "list"=>[
                "addressNames"=>["required","array"],
                "addressNames.*"=>["required",]
            ],
            // 入力が町ごとで、その町のすべてのとき
            "selectAll"=>[
                // prefがsqlに存在するか
                "prefName"=>["required"],
                // cityが存在するか/pref+cityがsqlに存在するか
                "cityName"=>["required"]
            ],
            // 入力が町ごとで、１町ずつ検索するとき
            "selectOneTown"=>[
                "addressId"=>["required","integer",new AddressExistsRule]
            ],
            default=>throw new BusinessException("町目の取得形式の異常です")
        };


        return [
            "staffIds"=>["present","array"],
            "staffIds.*"=>["integer",new StaffIsExistsRule],
            "startYear"=>['required', 'integer', 'between:1,11'],
            // 「終点が始点より後」だが、〜年前という渡し方のため「(数字が)startYearより小さい)」というルールに
            // lteはless than or equal
            "endYear"=>['required', 'integer', 'between:-1,10','lte:startYear'],
            ...$rules_by_pattern
        ];
    }

    public function messages(){
        return[
            "staffIds.present"=>"スタッフが予期せぬ値です",
            "staffIds.array"=>"スタッフが予期せぬ値です",
            "staffIds.*.integer"=>"スタッフが予期せぬ値です",
            "startYear.required"=>"開始年度が選択されていません",
            "startYear.integer"=>"開始年度の値が予期せぬものです",
            "startYear.between"=>"開始年度の値が予期せぬものです",
            "endYear.required"=>"終了年度が選択されていません",
            "endYear.integer"=>"終了年度の値が予期せぬものです",
            "endYear.between"=>"終了年度の値が予期せぬものです",
            "endYear.lte"=>"終了年度が開始年度より前になっています",
            "addressNames.required"=>"データが町目リストに入力されていません",
            "addressNames.array"=>"町目リストが想定されたものと違います",
            "prefName.required"=>"県の名前が取得できません",
            "cityName.required"=>"市の名前が取得できません",
            "addressId.required"=>"住所が取得できません",
            "addressId.integer"=>"住所の値が不正です",
        ];
    }
}
