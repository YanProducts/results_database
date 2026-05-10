<?php

// CSVエクスポートするidSetsのバリデーション
namespace App\Http\Requests\Clerical;

use App\Rules\Clerical\IdSetsInProjectRule;
use Illuminate\Foundation\Http\FormRequest;

class CSVExportRequest extends FormRequest
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
            //
            "idSets"=>["required","array",new IdSetsInProjectRule ],
        ];
    }

    public function messages(): array
    {
        return [
            //
            "idSets.required"=>"エクスポートする案件が選択されていません",
            "idSets.array"=>"予期せぬエラーです",
        ];
    }
}
