// 案件が識別番号では分かりにくので、町目ごとのサンプルを作成
// 後から作ったものなのでデータ構造が違うため、詳細版と分ける
// またmap選択のdom作成の処理と似通った面もあるが、dom作成処理においてはstateの変更があるため統一はしない

import React from "react";
import AssignProjectToStaffContext from "../../../../../../Contexts/BranchManager/useAssignProjectToStaffContexts";
import simpleGetProjectNameForView from "../../../../../../Support/BranchManager/simpleGetProjectNameForView";

export default function TownByProjectsForExplainForSimple({planIdsAndMapsByMainProjects,dateProjectsIndexInTheDate,pageMaxWidth,pageMinWidth}){

    const {showAllTowns,onShowAllTownsClick,onCloseAllTownsClick}=React.useContext(AssignProjectToStaffContext);

    // planIdsAndMapsByMainProjectsは[project_name=>round_number]
    // dateProjectIndexInTheDateは[project_name,round_number]
    //planIdsAndMapsByMainProjectsのtown_listsにaddress_nameを配列で入れる

    // その日に来ている案件
    const projectNamesWithRaoundNumberInTheDate= dateProjectsIndexInTheDate.map((eachIndexSet)=>eachIndexSet.project_name + "|" + Number(eachIndexSet.round_number));


    //プロジェクトのデータセットで、その日の案件に入っていないデータはフィルター(入れ子での条件設定なのでfilterは使えない)
    // project直下のroundNumber以下のものをなくしていく
    let filteredPlanIdsAndMapsByMainProjects={}
    Object.entries(planIdsAndMapsByMainProjects).forEach(function([projectName,valueInProjectName]){
        Object.entries(valueInProjectName).forEach(function([roundNumber,eachSet]){
        if(projectNamesWithRaoundNumberInTheDate.includes(projectName + "|" + Number(roundNumber))){
            if(!Object.hasOwn(filteredPlanIdsAndMapsByMainProjects,projectName)){
                filteredPlanIdsAndMapsByMainProjects[projectName]={}
            }
            filteredPlanIdsAndMapsByMainProjects[projectName][roundNumber]=eachSet
        };
        })
  })

    // マップの表示用の案件名と統一(PopIpMapChoiceByStaffを参照する)
    return(
         <div className={`base_frame base_backColor px-5 border border-black rounded-md my-3 ${pageMaxWidth,pageMinWidth}`}>
            <p className="mt-0 mb-1 font-bold text-left px-3 pt-1  border-b border-black">町目一覧（案件把握用）</p>
            {Object.entries(filteredPlanIdsAndMapsByMainProjects).map(function([projectName,valueInProjectName]){
                return(
                Object.entries(valueInProjectName).map(function([roundNumber,eachSet],roundNumberIndex){

                    // そのプロジェクトがその回において1度ならroundNumberは表示しない。1度ではない場合は、roundNumberの登場が何回目かで表示
                    const projectNameForView=simpleGetProjectNameForView({valueInProjectName,projectName,roundNumberIndex});

                    // 住所セット
                    const townStr=eachSet.address_sets.join("、");

                    return(
                        <React.Fragment key={projectNameForView}>
                        <div className={`${showAllTowns==projectNameForView ? "bg-amber-200" : ""}`}>{`${projectNameForView}…${townStr.substring(0,50)}`}
                            {townStr.length>50 &&
                            <span>.....<span className="base_link_p" onClick={(e)=>onShowAllTownsClick({e,projectNameForView})}>全表示</span></span>
                            }
                        </div>
                        {showAllTowns==projectNameForView &&
                        <div id="popUpForShowAllTowns" className="bg-lime-300 border-black border-2 rounded-sm p-2 w-[80%]">
                            <p className="my-1 border-b border-black text-center py-1">{projectNameForView}</p>
                            <p className="my-1">{townStr}</p>
                            <p onClick={onCloseAllTownsClick} className="my-1 base_link_p text-center">閉じる</p>
                        </div>
                        }
                        </React.Fragment>
                    )
                })
            )
            })
            }
        </div>
      )

}
