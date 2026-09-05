<?php

namespace App\Rules\Common;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Constants\Date as ConstantsDate;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;

// 報告書の日時が予想されたものか
// 営業所長の編集時か、スタッフの投稿時か、事務員の投稿時で判定
class ReportDateRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    public function __construct(public bool $is_edit)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $now=CarbonImmutable::now();

        // その時のroleによって変更する
        $start_offset=match(Auth::user()->role){
            "field_staff"=>$this->is_edit ? "" :ConstantsDate::StartOffsetInReportPeriod,
            "branch_manager"=>ConstantsDate::StartOffsetInConfirmReportPeriodForManager ,
            "clerical"=>""
        };
        $end_offset=match(Auth::user()->role){
            "field_staff"=>$this->is_edit ? "" :ConstantsDate::EndOffsetInReportPeriod,
            "branch_manager"=>ConstantsDate::EndOffsetInConfirmReportPeriodForManager ,
            "clerical"=>""
        };

        $start_date=$now->addDays($start_offset)->toDateString();
        $end_date=$now->addDays($end_offset)->toDateString();

        if($value<$start_date || $value>$end_date){
            $fail("対象外の日時です");
        }

    }
}
