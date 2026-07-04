import React from "react";

// 各案件の合計数
export default function TrForSum({projectSets,isConfirm,sumSets}){

    return(
        <React.Fragment>

         <tr className="border-black border-2 base_backColor">
            <td className="border-black border-2 bg-sky-200 font-bold" colSpan={2}>{`合計${isConfirm ? "" :"：町目ごと"}`}</td>
            {/* それぞれの案件の合計数 */}
             {Object.keys(projectSets).map((projectId,index)=>
                <td key={index} className={`border-black border-x-2`}>{sumSets[projectId]?.sumByTowns || "-"}</td>
            )}
        </tr>

       {!isConfirm &&
       <React.Fragment>
        <tr className={`border-black border-2 base_backColor`}>
            <td className="bg-orange-300 border-x-2 border-black" colSpan={2}>合計：持ち出し-返却</td>
             {Object.keys(projectSets).map(function(projectId,index){
            const sumByCounts=sumSets[projectId]?.sumByCounts || null;
                return(
                    <td className={`border-x-2 border-black ${(sumByCounts && sumByCounts<0) ? "text-red-500 font-bold" :"text-black"}`} key={index}>{sumSets?.[projectId]?.textForDistributionCounts || "-"}</td>
                )
             }
            )}
        </tr>

        <tr className={`border-black border-2 base_backColor`}>
            <td className="bg-orange-300 border-x-2 border-black" colSpan={2}>ズレ</td>
            {Object.keys(projectSets).map(function(projectId,index){
                return(
                <td key={index} className={`border-x-2 font-bold border-black ${sumSets?.[projectId]?.difference==0 ? "text-black" : "text-red-500"}`}>{sumSets?.[projectId]?.textForDifference || "-"}</td>
               )
            })}
        </tr>
        </React.Fragment>
        }
     </React.Fragment>
    )
}
