import React from "react";
import WriteReportContext from "../../../../Contexts/FieldStaffs/useWriteReportContexts";
import MainTdInner from "./MainTdinner";
import SubTdInner from "./SubTdInner";

// 報告書入力のtableの中身(各Trの行)
export default function TrInner({mainProjectName,projectSets,eachData,mapNumber,trIndex,indexInMaps,widthSets,onAssignedInputChange,inputRefs,inputValues,onInputKeyDown,isConfirm,processing,fromSimpleFlag,changedData}){

        const {isBigMedia,isEdit=false}=React.useContext(WriteReportContext);

        const assignId=eachData.assign_id;

        // 編集と作成とで併配案件のidセットの取得元を分ける
        const subProjectIdSets=isEdit ? Object.keys(eachData.sub_sets) : eachData.sub_sets;

        return(
                <tr className={`border-black border-2 ${indexInMaps == 0 ? "border-t-3" : "border-t-2"} base_backColor`} key={trIndex}>
                   <td className={`border-black border-x-2 whitespace-pre-wrap ${widthSets[0]} `}>{isBigMedia ? eachData.address_name_when_big_media : eachData.address_name}</td>
                    <td className={`border-black border-x-2 bg-yellow-300 ${widthSets[1]}`}>{eachData.household}</td>
                    {/* 案件ごとの配布数 案件数によって数を変化 */}
                    {Object.keys(projectSets).map(function(eachSet,index){

                        // メイン案件
                        if(index==0){
                            return(
                                <td key="main" className={`border-black border-x-2 ${widthSets[2]} ${(isConfirm && !inputValues?.[mainProjectName]?.[assignId]?.["main"]) && "ui_attention"}`}>
                                    <MainTdInner {...{isConfirm,onAssignedInputChange,assignId,mainProjectName,trIndex,indexInMaps,index,inputValues,inputRefs,onInputKeyDown,processing,fromSimpleFlag,changedData}} />
                                </td>
                       )}

                   // 併配案件リストのid
                   const subProjectId=Number(eachSet.substring(2))

                        // 併配セット
                        return(
                            subProjectIdSets.map(subProjectIdInnAssignSets=>Number(subProjectIdInnAssignSets)).includes(subProjectId) ?
                            <td key={"sub" + index} className={`border-black border-x-2 ${widthSets[index+2]} ${(isConfirm && !inputValues?.[mainProjectName]?.[assignId]?.[subProjectId]) &&  "ui_attention"} `}>
                             <SubTdInner {...{isConfirm,onAssignedInputChange,assignId,subProjectId,mainProjectName,trIndex,indexInMaps,index,inputValues,inputRefs,onInputKeyDown,processing,fromSimpleFlag,changedData}}/>
                            </td>
                        :
                        <td key={"sub" + index} className={`border-black border-x-2 ${widthSets[index+2]}`}>-</td>
                        )
                    })}
                 <td className={`border-black border-x-2 font-bold ${widthSets[widthSets.length-1]} bg-orange-200`}>{mapNumber}</td>
            </tr>
    )
}
