<?php

namespace App\Http\Requests\BranchManager;

use App\Rules\BranchManager\StaffIsWorkingRule;
use App\Rules\Common\StaffIsExistsRule;
use Illuminate\Foundation\Http\FormRequest;

// 案件のみの
class ProjectOnlyAssignRequest extends FormRequest
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
            "staffId"=>["required","integer",new StaffIsExistsRule,new StaffIsWorkingRule],
            "projectPlanIds"=>["present","array",],


            "projectPlanIds.*"=>["integer",] //これはrequiredになるのか！？

        ];
    }
}
