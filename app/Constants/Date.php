<?php

namespace App\Constants;
// 日付に関する定数

class Date{
    // スタッフに割り当てを行うページで今日の何日後までの割り当てをできるようにするか
    public const StartOffsetInStaffAssignMent=0;
    public const EndOffsetInStaffAssignMent=7;

    // スタッフが投稿する報告書は何日前から何日後のものにするか
    public const StartOffsetInReporPeriod=-2;
    public const EndOffsetInReportPeriod=5;

}

