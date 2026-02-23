<?php

namespace App\Http\Requests\WholeData;

use App\Rules\WholeData\PlaceExistsRule;
use App\Rules\WholeData\RoleExistsRule;
use App\Rules\WholeData\StaffNameRule;
use App\Rules\WholeData\UserNameNotExistsRule;
use App\Rules\WholeData\UserNameRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
class ProvisionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    //事前登録に関するバリデーション

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
        
        // 分岐や引数のためのroleの取得(後に文字列取得があるので、何もない場合はnullではなく空文字で取得)。roleのバリデーションによる比較は、別途行われる
        $role=$this->input("role") ?? "";

        //個別ルール
        $rules=[];

        // 営業所名(営業所担当と現場作業員には必要)
        // SQLに登録されている営業所銘菓を調べる
       if(in_array($role,["field_staff","branch_manager"])){
          // 登録済のものから使うこと
           $rules["place"]=["required",new PlaceExistsRule];
       }else{
        // それ以外だと、営業所名は入力に関わらずスルーされる
       }

       // スタッフ名
       // 現場作業員のみ。入力必須ではないが入力するなら全角の文字のみ)
        if($role=="field_staff"){
            // 現場作業員のみスタッフ名が必要（空文字許容）
            $rules["staffName"]=[new StaffNameRule];
        }else{
        // それ以外だと、スタッフ名は入力に関わらずスルーされる
        }

        //個別ルールを展開し、全体ルールを足す
        return [
            // role //存在しているroleかどうか
            "role"=>["required", new RoleExistsRule],
            // ユーザー名
            // 現時点では半角英数字のみ定義:今後routeによって分割させる可能性あり
            // そのroleで登録されている名前ならアウト(roleが存在しない場合は事前に弾かれている)
            // Laravel11では全角スペースもtrimしてくれる
            "userName"=>["required","min:2","max:50",new UserNameRule,new UserNameNotExistsRule($role)],
            ...$rules,
        ];
    }
    public function messages(): array
    {
        return [
            // role
            "role.required"=>"担当は必ず選択してください",
            //ユーザー名
            "userName.required"=>"ユーザー名は必ず入力してください",
            "userName.min"=>"ユーザー名は２文字以上必要です",
            "userName.max"=>"ユーザー名は５０文字以内にしてください",
            // 営業所名
            "place.required"=>"営業所は入力必須です"
        ];
    }
}
