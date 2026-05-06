<?php

namespace App\Rules\FieldStaff;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Constants\Date as ConstantsDate;
use Carbon\CarbonImmutable;

// 報告書の日時が予想されたものか
class ReportDateRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $now=CarbonImmutable::now();

        $start_date=$now->addDays(ConstantsDate::StartOffsetInReportPeriod)->toDateString();
        $end_date=$now->addDays(ConstantsDate::EndOffsetInReportPeriod)->toDateString();

        if($value<$start_date || $value>$end_date){
            $fail("対象外の日時です");
        }

    }
}
