<?php

namespace App\Http\Requests\ProjectOperator;

use App\Rules\ProjectOperator\ProjectsExistsRule;
use Illuminate\Foundation\Http\FormRequest;

class ConfirmRequest extends FormRequest
{
    // 重複している案件が、既存のものか新規のものか
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
        // プロジェクトリストの存在、配列か、各々のプロジェクト名が存在するか
        return [
            //配列自体は存在すれば空でも良い
            "newProjects"=>["required","array"],
            "newProjects.*"=>[new ProjectsExistsRule]
        ];
    }
    public function massages(): array
    {
        // プロジェクトリストの存在、配列か、各々のプロジェクト名が存在するか
        return [
            "newProjects.required"=>"データがありません",
            "newProjects.array"=>"予期せぬエラーです",
        ];
    }
}
