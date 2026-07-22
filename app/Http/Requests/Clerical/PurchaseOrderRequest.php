<?php

namespace App\Http\Requests\Clerical;

use App\Rules\Common\StaffIsExistsRule;
use Illuminate\Foundation\Http\FormRequest;

// 発注書の投稿におけるバリデーション
class PurchaseOrderRequest extends FormRequest
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
            //スタッフ...存在するかも含む
            "staffId"=>["required","integer",new StaffIsExistsRule],
            // 開始月
            "startMonth"=>["required","date"],
            // 終了月(開始月と同等より後であること)
            "endMonth"=>["required","date",'after_or_equal:start_date']
        ];
    }
    public function messages(): array
    {
        return [
            "staffId.required"=>"スタッフが投稿されていません",
            "staffId.integer"=>"スタッフIdの値が不正です",
            "startMonth.required"=>"開始月が投稿されていません",
            "startMonth.date"=>"開始月が日時形式ではありません",
            "endMonth.required"=>"終了月が投稿されていません",
            "endMonth.date"=>"終了月が日時形式ではありません",
            "endMonth.after_or_equal"=>"開始日が終了日より後ろです",
        ];
    }
}
