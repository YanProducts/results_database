import BaseTable from "../../../../../Common/BaseTable";
import AssignProjectToStaffContext from "../../../../../../Contexts/BranchManager/useAssignProjectToStaffContexts";
import React from "react";

// 案件版で確認の際のスタッフごとに区分けして案件を見ていくテーブル
export default function TableByStaffsForConfirm({selectedDate,pageMaxWidth,pageMinWidth,staffsInSelectedDate,choicedProjects}){
    const {dateProjectsIndex}=React.useContext(AssignProjectToStaffContext);
    const projectSetsInTheDate=dateProjectsIndex[selectedDate];
    return(
        <BaseTable tableTheme={`日付：${new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"})}`}
        thSets={{"staff":"スタッフ名","confirm":"選択した案件"}}
        width="w-[90%]" thWidthSets={["w-[30%]","w-[70%]"]}
        maxWidth={pageMaxWidth} minWidth={pageMinWidth}>
            {Object.entries(staffsInSelectedDate).map(function([staffId,staffName],index){
                const projectIds=choicedProjects?.[staffId] || [];
                return(
                    // スタッフごとに列を並べる
                    <tr className="border-2 border-black" key={index}>
                            {/* スタッフ名 */}
                            <td  className={`border-2 border-black w-[20%] align-middle text-center base_backColor"}`}>{staffName}</td>
                            {/* 案件 */}
                            <td className="w-[80%]">
                                {(projectIds.map(eachProjectId=>projectSetsInTheDate["id_"+eachProjectId].project_name_for_view)).join("、")}
                            </td>
                    </tr>
                )
              }
            )}
        </BaseTable>
    )
}
