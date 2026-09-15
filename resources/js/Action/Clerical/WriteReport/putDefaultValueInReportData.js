// 入力担当が入力する全体の報告書に、初期値を入れる
// 途中でリセットする場合もあるので、definitionではなくactionの下層で定義
export default function putDefaultValueInReportData({reportDataInTheProject}){
    return(
        Object.entries(reportDataInTheProject).map(function([planId,eachData],index){
            return{
                "planId":planId,
                "totalCount":eachData.total_counts
            }
        })
    )
}
