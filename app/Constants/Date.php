<?php

namespace App\Constants;
// 日付に関する定数

class Date{

    // 何ヶ月前から案件一覧を見せるか
    public const ProjectOverviewStartDateOffsetMonth=1;

    // スタッフに割り当てを行うページで今日の何日後までの割り当てをできるようにするか
    public const StartOffsetInStaffAssignMent=0;
    public const EndOffsetInStaffAssignMent=7;

    // スタッフが投稿する報告書は何日前から何日後のものにするか
    public const StartOffsetInReportPeriod=-2;
    public const EndOffsetInReportPeriod=5;

    // スタッフが確認する報告書は何日前から何日後のものにするか
    public const StartOffsetInConfirmReportPeriod=-2;
    public const EndOffsetInConfirmReportPeriod=2;

    // 営業所長が確認する報告書は何日前から何日後のものにするか(もっと前からも選ぶことは可能にする)
    public const StartOffsetInConfirmReportPeriodForManager=-30;
    public const EndOffsetInConfirmReportPeriodForManager=5;

    // 入力担当が入力できる報告書は営業所に振られた最終配布日の何日後までOKか
    public const EndOffsetForClericalExport=-90;

    //発注書リストのデータリストの初期表示は何ヶ月前からのものにするか
    public const PurchaseListDefaultMonthsBack=36;

    // 結果検索ができるのは何年前を限度に設定か(これ以上は無制限の年度取得)
    public const ResultSerachBeforeYearLimit=10;

}

