import React from "react";

// 一定期間の報告書一覧の定義
export default function useReportOverviewDefinition(){

        // フォームはいらない

        // バリデーションの非表示
        const [validationHidden,setValidationHidden]=React.useState(false);


        // ページの横幅
        const [pageMinWidth,pageMaxWidth]=[     "min_width_when_write_report",
        "max_width_when_write_report"];

        return {pageMinWidth,pageMaxWidth,validationHidden,setValidationHidden};

}
