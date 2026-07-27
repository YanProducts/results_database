<?php

namespace App\Http\Requests\BranchManager;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            //
        ];
    }
}
