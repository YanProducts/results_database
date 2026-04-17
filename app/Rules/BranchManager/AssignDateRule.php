<?php

namespace App\Rules\BranchManager;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Constants\Date as ConstantsDate;

class AssignDateRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        // 本日
        $now=new \DateTimeImmutable();

        // 渡された日
        $date=new \DateTimeImmutable($value);

        // 案件候補の表示開始日(正負によって分ける=前日の報告書を書きたい時など)
        $start_offset=ConstantsDate::StartOffsetInStaffAssignMent;
        $start_date=
        $start_offset>=0 ?
        $now->add(new \DateInterval("P".$start_offset."D"))
        :$now->sub(new \DateInterval("P".abs($start_offset)."D"));

        // 案件候補の表示終了日(確実に正の数)
        $end_date=$now->add(new \DateInterval("P".ConstantsDate::EndOffsetInStaffAssignMent."D"));

        if($start_date>$date || $end_date<$date){
            $fail("日付が期間外です");
        }

    }
}
