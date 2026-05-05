import MainTdInner from "./MainTdinner";
import SubTdInner from "./SubTdInner";

// 報告書入力のtableの中身
export default function TbodyInner({mainProjectName,projectSets,eachData,trIndex,widthSets,onAssignedInputChange,inputRefs,inputValues,isConfirm}){
        const assignId=eachData.assign_id;
        return(
                <tr className="border-black border-2 base_backColor" key={trIndex}>
                    <td className={`border-black border-2 ${widthSets[0]}`}>{eachData.address_name}</td>
                    <td className={`border-black border-2 bg-yellow-300 ${widthSets[1]}`}>{eachData.household}</td>
                    {/* 案件ごとの配布数 案件数によって数を変化 */}
                    {Object.keys(projectSets).map(function(eachSet,index){
                        const subProjectId=Number(eachSet.substring(2))
                        // メイン案件
                        if(index==0){
                            return(
                                <td key="main" className={`border-black border-2 ${widthSets[2]} ${(isConfirm && !inputValues?.[mainProjectName]?.[assignId]?.["main"]) && "ui_attention"}`}>
                                    <MainTdInner {...{isConfirm,onAssignedInputChange,assignId,mainProjectName,trIndex,index,inputValues,inputRefs}} />
                                </td>
                       )}
                        // 併配セット
                        return(
                            eachData.sub_sets.map(subProjectIdInnAssignSets=>Number(subProjectIdInnAssignSets)).includes(subProjectId) ?
                            <td key={"sub" + index} className={`border-black border-2 ${widthSets[index+2]} ${(isConfirm && !inputValues?.[mainProjectName]?.[assignId]?.[subProjectId]) &&  "ui_attention"} `}>
                             <SubTdInner {...{isConfirm,onAssignedInputChange,assignId,subProjectId,mainProjectName,trIndex,index,inputValues,inputRefs}}/>
                            </td>
                        :
                        <td key={"sub" + index} className={`border-black border-2 ${widthSets[index+2]}`}>-</td>
                        )
                  })}
            </tr>
    )
}
