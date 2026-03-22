<?php

namespace App\Http\Requests\ProjectOperator;

use App\Rules\WholeData\PlaceExistsRule;
use Illuminate\Foundation\Http\FormRequest;

// 案件割り当てのバリデーション
class DispatchRequest extends FormRequest
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
            // 営業所
            "place"=>["required",new PlaceExistsRule],
            // ファイルの大元
            "fileSets" => ["required", "array","max:20"],
            // 各ファイル(別途、ruleで例外除去)
            "fileSets.*" => ["file", "mimes:csv", "mimetypes:text/csv,text/plain","max:2048"],
        ];
    }
    public function messages(){
        return[
            "place.required"=>"営業所は選択必須です",
            "fileSets.required"=>"ファイルが選択されていません",
            "fileSets.array"=>"ファイル選択でエラーが発生しました",
            "fileSets.*.file"=>"ファイル選択でエラーが発生しました",
            "fileSets.*.mimes"=>"ファイルがcsvではありません",
            "fileSets.*.mimetypes"=>"ファイルの内容がcsvではありません",
            "fileSets.*.max"=>"ファイルサイズが最大を超えています"

        ];
    }
}
