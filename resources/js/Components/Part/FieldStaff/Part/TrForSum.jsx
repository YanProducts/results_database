import React from "react";
// 各案件の合計数
export default function TrForSum({inputValues,mainProjectName,projectSets,widthSets,issuedCount,returnedCount}){

    const issuedCountByMainProject=issuedCount?.[mainProjectName] || "";
    const returnedCountByMainProject=returnedCount?.[mainProjectName] || "";
    const inputValuesByMainProject=inputValues[mainProjectName];

    // 先に全ての合計数の取得
    const sumSets=
    Object.fromEntries(Object.entries(projectSets).map(function(projectIdNameSet,index){
        const projectId=projectIdNameSet[0];
        const projectName=projectIdNameSet[1];

        return [[projectId],{
            // 町目ごと部数からの合計数
            "sumByTowns":
                // メインの合計数
                index==0 ?
                    inputValuesByMainProject ? Object.values(inputValuesByMainProject).reduce((nowSumValue,valueInArray)=>nowSumValue+Number(valueInArray?.main || 0),0) : 0
                :
                    inputValuesByMainProject ? Object.values(inputValuesByMainProject).reduce((nowSumValue,valueInArray)=>nowSumValue+Number(valueInArray[projectId.substring(2)] ? valueInArray[projectId.substring(2)] : 0),0) : 0
            ,
            // 配った枚数からの合計数
            "sumByCounts":
            (issuedCountByMainProject[projectName] && returnedCountByMainProject[projectName]) ?
            issuedCountByMainProject[projectName]-returnedCountByMainProject[projectName] : 0,
        }];
    }))

    console.log(sumSets) //ここはとれてる

    return(
        <React.Fragment>

         <tr className="border-black border-2 base_backColor">
            <td className="border-black border-2 bg-sky-200" colSpan={2}>合計</td>
            {/* それぞれの案件の合計数 */}
             {Object.keys(projectSets).map((projectId,index)=>
                <td key={index} className={`border-black border-x-2`}>{sumSets[projectId]?.sumByTowns || "-"}</td>
            )}
        </tr>


        <tr className={`border-black border-2 base_backColor`}>
            <td className="bg-orange-300 border-x-2 border-black" colSpan={2}>持ち出し-返却</td>
             {Object.keys(projectSets).map((projectIdNameSets,index)=>
            <td className="border-x-2 border-black" key={index}>{sumSets[projectIdNameSets]?.sumByCounts || -""}</td>
            )}
        </tr>

        <tr className={`border-black border-2 base_backColor`}>
            <td className="bg-orange-300 border-x-2 border-black" colSpan={2}>ズレ</td>
            {Object.keys(projectSets).map(function(projectId,index){
                const sumByTowns=sumSets?.[projectId]?.sumByTowns || 0;
                const sumByCounts=sumSets?.[projectId]?.sumByCounts || 0;

                const difference=sumByTowns - sumByCounts;
                const sentence=(sumByCounts==0 && sumByTowns ==0) ? "-" :(difference > 0 ? difference +"多い" :(difference < 0 ? difference + "少ない" : "ＯＫ！"))

                return(
                <td key={index} className={`border-x-2 font-bold border-black ${difference==0 ? "text-black" : "text-red-500"}`}>{sentence}</td>
               )
            })}
        </tr>
     </React.Fragment>
    )
}
