// 一定期間の報告書一覧の定義
export default function useReportOverviewDefinition(){
          // フォームはいらないかも(クリックでそのまま詳細に行くため)

           // ページの横幅
        const [pageMinWidth,pageMaxWidth]=["min-w-90 mobile:min-w-250","max-w-300 mobile:max-w-400"];

        return {pageMinWidth,pageMaxWidth};

}
