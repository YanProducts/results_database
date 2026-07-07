import React from "react";
import WriteReportContext from "../../../../Contexts/FieldStaffs/useWriteReportContexts";

// 各案件の合計数
export default function TrForSum({mainProjectName,projectSets,isConfirm,sumSets}){

    // 最初に宣言すると、入れ子の最後まで使えるのがcontext(知ったのが途中からだったので、今から使用)
    const {onSetOtherProjectToSameValueClick}=React.useContext(WriteReportContext)

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

        <tr className={`border-black border-2 base_backColor`}>
            <td className="bg-orange-300 border-x-2 border-black" colSpan={2}>同数セット</td>
            {Object.keys(projectSets).map(function(projectId,index){
                return(
                <td key={index} className={`border-x-2 font-bold border-black py-2`}> <button className={`base_btn w-[80%] ${(sumSets?.[projectId]?.difference==0  && sumSets?.[projectId]?.textForDistributionCounts>0 )? "active_btn" : "non_active_btn"} text-sm` } onClick={(e)=>{onSetOtherProjectToSameValueClick(e,mainProjectName,projectId,index)}}>{index== 0 ? "併配" : "メイン\n"}同数</button></td>
               )
            })}
        </tr>

        </React.Fragment>
        }
     </React.Fragment>
    )
}
