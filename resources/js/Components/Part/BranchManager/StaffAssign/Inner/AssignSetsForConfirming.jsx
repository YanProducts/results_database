import BaseTable from "../../../../Common/BaseTable";

export default function AssignSetsForConfirming({assignPlanForConfirmView,pageMinWidth,pageMaxWidth}){

    return(
        <div className={`base_frame ${pageMinWidth} ${pageMaxWidth} mt-2 mb-4`}>
            {/* 基本はbaseの形のtableを使いつつ、tbosyの中身のみカスタム */}
            <BaseTable tableTheme="割り当ての確認" thSets={{"staff":"スタッフ名","project":"案件名","map":"地図の番号","town":"町目"}} allData={"カスタムなので除外"} pageMaxWidth={pageMaxWidth} pageMinWidth={pageMinWidth} width={"w-[80%]"}>
                {/* スタッフId:メイン案件:案件Idセットの文字列のオブジェクトを見て行く */}
                {Object.entries(assignPlanForConfirmView).map(function(eachPlanByStaff,index){
                    const staffName=eachPlanByStaff[0];
                    const projectsToStaff=Object.keys(eachPlanByStaff[1]);

                    return(
                    // スタッフが担当するメイン案件の数によって行の数が変更するためprojectNameでmap
                    projectsToStaff.map(function(projectName,innerIndex){
                        const eachPlanByStaffInTheProject=eachPlanByStaff[1][projectName];
                        return(
                        <tr className="border-black border-2" key={ index + "-" + innerIndex}>
                                {/* スタッフ名 */}
                                {innerIndex ==0 &&
                                <td className="border-black border-2 w-[25%]" rowSpan={projectsToStaff.length}>{staffName}</td>
                                }
                                {/* 案件名 */}
                                <td className="border-black border-2 w-[25%]">{projectName}</td>
                                {/* マップ */}
                                <td className="border-black border-2 w-[15%]">
                                {eachPlanByStaffInTheProject.mapComment}
                                </td>
                                {/* 町目名(idを変更させる必要あり) */}
                                <td className="border-black border-2 w-[35%] px-2 whitespace-pre-line text-left">{eachPlanByStaffInTheProject.planId.join("\n")}</td>
                            </tr>
                        )})
                    )
                 })}
            </BaseTable>
        </div>
    )

}
