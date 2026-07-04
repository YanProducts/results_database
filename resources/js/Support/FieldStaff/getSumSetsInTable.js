// 後に全体囲いにして消す

// trの合計値の取得
export default function getSumSetsInTable({projectSets,inputValues,issuedCount,returnedCount,mainProjectName}){

    // その案件の持ち出し・返却・町目ごとの合計の取得
    const issuedCountByMainProject=issuedCount?.[mainProjectName] || "";
    const returnedCountByMainProject=returnedCount?.[mainProjectName] || "";
    const inputValuesByMainProject=inputValues[mainProjectName];

    return (
        Object.fromEntries(Object.entries(projectSets).map(function(projectIdNameSet,index){

        const projectId=projectIdNameSet[0];
        const projectName=projectIdNameSet[1];

        // 町目ごとの世帯数からの合計
        const sumByTowns=   // indexの0がメインの合計数
                index==0 ?
                    inputValuesByMainProject ? Object.values(inputValuesByMainProject).reduce((nowSumValue,valueInArray)=>nowSumValue+Number(valueInArray?.main || 0),0) : 0
                :
                    inputValuesByMainProject ? Object.values(inputValuesByMainProject).reduce((nowSumValue,valueInArray)=>nowSumValue+Number(valueInArray[projectId.substring(2)] ? valueInArray[projectId.substring(2)] : 0),0) : 0;

        // 持ち出し-返却の合計数
        const sumByCounts=(issuedCountByMainProject[projectName] || returnedCountByMainProject[projectName]) ? (issuedCountByMainProject[projectName] ?? 0)-(returnedCountByMainProject[projectName] ?? 0): 0;

        // 町目ごと部数と配布数の差異
        const difference=sumByTowns - sumByCounts;


        return [[projectId],{
            // 町目ごと部数からの合計数
            "sumByTowns":sumByTowns,
            // 配った枚数からの合計数
            "sumByCounts":sumByCounts,
            // ずれ
            "difference":difference,
            // 配布数のところに載せる文面
            "textForDistributionCounts":sumByCounts ? (sumByCounts>0 ? sumByCounts :"返却の方が多い") : "",
            // ずれのところに載せる文面
            "textForDifference":(sumByCounts==0 && sumByTowns ==0) ? "-" :(difference > 0 ? difference +"多い" :(difference < 0 ? Math.abs(difference) + "少ない" : "ＯＫ！"))
        }];
    }))
    )

}
