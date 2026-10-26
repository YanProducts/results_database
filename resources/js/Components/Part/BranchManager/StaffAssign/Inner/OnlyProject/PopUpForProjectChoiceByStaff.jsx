import React from "react";
import simpleGetProjectNameForView from "../../../../../../Support/BranchManager/simpleGetProjectNameForView";

// スタッフの欄のクリック時に表示される案件のリストをpopUp
// 複数選択の時のpopUp(divで表示して、checkBoxに格納し、React連動)

export default function PopUpForProjectChoiceByStaff({popUpVisible,staffInChoice,choicedProjects,selectedDate,onProjectDecide,onProjectChoiceClose,dateProjectsIndex}){

    //その日に振られている[project_plan_Id,project_name_for_view]
    const projectsInTheDate= dateProjectsIndex[selectedDate];

    return(
            <div id="popUpForMultiSort" className={`${popUpVisible ? "block" :"hidden"} absolute z-20 w-40`}>
                {
                Object.values(projectsInTheDate).map(function(eachProject,index){
                        const projectPlanId=eachProject.project_plan_id;
                        const projectNameForView=eachProject.project_name_for_view;
                        // このプロジェクトが選択されているか
                        const isChecked=(choicedProjects?.[staffInChoice] || []).includes(projectPlanId);
                        // プロジェクト名
                        return(
                                <div className={`flex items-center justify-center border border-black ${isChecked ? "border-2 bg-green-300 hover:bg-amber-50" :"bg-amber-100 hover:bg-lime-100 border-b border-x"}`} key={projectPlanId}>
                                    <input className="hidden" type="checkbox" multiple id={projectPlanId} name={`${staffInChoice}_${projectPlanId}[]`}
                                    value={projectPlanId}
                                    onChange={(e)=>{onProjectDecide(e,projectPlanId)}} checked={isChecked}></input>
                                    {/* 背景色は動的に変更するように後で設定 */}
                                    <label className="text-center w-full cursor-pointer" htmlFor={projectPlanId}>{projectNameForView}</label>
                                </div>
                            )}
                    )
                }
                <div className={`flex items-center justify-center border-x border-b  border-black bg-sky-200 hover:bg-red-200 cursor-pointer`} onClick={onProjectChoiceClose}>閉じる</div>
                  <p>　</p>
            </div>
    )
}
