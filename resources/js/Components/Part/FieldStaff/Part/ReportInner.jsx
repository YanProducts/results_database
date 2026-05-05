import React from "react";
import BaseTable from "../../../Common/BaseTable";
import InputTbody from "./InputTbody";
import setTdWidthByProjectCounts from "../../../../Support/WriteReport/setTdWidthByProjectCounts";

// 報告書テーブルの内部
export default function ReportInner({pageMinWidth,pageMaxWidth,assignDataToStaff,selectedDate,onAssignedInputChange,inputRefs,inputValues,isConfirm}){
    return(
         Object.entries(assignDataToStaff[selectedDate]).map(function(keyValueSets,index){
                // プロジェクトの数に応じてthやtdの長さの変化
                const widthSets=setTdWidthByProjectCounts(Object.keys(keyValueSets[1]["project_set"]).length)

                const mainProjectName=keyValueSets[0];
                const projectSets=keyValueSets[1]["project_set"];
                const dataInEachMainProject=keyValueSets[1]["each_data"]

                return(
                <React.Fragment key={index}>

                <BaseTable tableTheme={mainProjectName} width={"w-[97.5%]"} thSets={{"town":"町名","household":"世帯数",...projectSets}} thWidthSets={widthSets} maxWidth={pageMaxWidth} minWidth={pageMinWidth} allData={[]} mb={"mb-4"}>
                {/* その日そのメイン案件におけるセットが「keyValueSets」で、それを町目ごとに見ていく */}
                {Object.values(dataInEachMainProject).map((eachData,trIndex)=>
                // テーブルの中身
                <InputTbody key={trIndex} {...{mainProjectName,projectSets,eachData,trIndex,widthSets,onAssignedInputChange,inputRefs,inputValues,isConfirm}}/>
                )}
                </BaseTable>
                </React.Fragment>
            )})

    )

}
