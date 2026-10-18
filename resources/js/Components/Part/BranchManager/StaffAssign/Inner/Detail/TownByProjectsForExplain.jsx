// 案件が識別番号では分かりにくので、町目ごとのサンプルを作成
import React from "react";
import AssignProjectToStaffContext from "../../../../../../Contexts/BranchManager/useAssignProjectToStaffContexts";
export default function TownByProjectsForExplain({projectsWithRoundNumberObject,projectsAndTowns}){
    const {showAllTowns,onShowAllTownsClick,onCloseAllTownsClick}=React.useContext(AssignProjectToStaffContext);
    return(
    Object.keys(projectsWithRoundNumberObject).length>0 ?
    <div className={`base_frame base_backColor w-full px-5 border border-black rounded-md my-3`}>
        <p className="mt-0 mb-1 font-bold text-left px-3 pt-1  border-b border-black">町目一覧（案件把握用）</p>
        {Object.keys(projectsWithRoundNumberObject).map(function(eachProjectKey){

            const townStr=(projectsAndTowns?.[eachProjectKey]?.each_sets || []).map(eachPlan=>eachPlan.address_name).join("、")

            const projectNameForView=projectsWithRoundNumberObject[eachProjectKey];

            return(
                <React.Fragment key={eachProjectKey}>
                 <div className={`${showAllTowns ? "bg-amber-200" : ""}`}>{`${projectNameForView}…${townStr.substring(0,50)}`}
                    {townStr.length>50 &&
                    <span>.....<span className="base_link_p" onClick={(e)=>onShowAllTownsClick({e,eachProjectKey})}>全表示</span></span>
                    }
                </div>
                {showAllTowns==eachProjectKey &&
                <div id="popUpId" className="bg-lime-300 border-black border-2 rounded-sm p-2 w-[80%]">
                    <p className="my-1 border-b border-black text-center py-1">{eachProjectKey}</p>
                    <p className="my-1">{townStr}</p>
                    <p onClick={onCloseAllTownsClick} className="my-1 base_link_p text-center">閉じる</p>
                </div>
                }
                </React.Fragment>
            )
         })}



    </div>
    :null
    )

}
