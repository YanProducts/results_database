import React from "react";
import BaseTable from "../../../Common/BaseTable";
import TbodyInner from "./TbodyInner";
import setTdWidthByProjectCounts from "../../../../Support/FieldStaff/setTdWidthByProjectCounts";
import TrForSum from "./TrForSum";

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

                <BaseTable tableTheme={mainProjectName} width={"w-[97.5%]"} thSets={{"town":"町名","household":"世帯数",...projectSets,"mapNumber":"地図番号"}} thWidthSets={widthSets} maxWidth={pageMaxWidth} minWidth={pageMinWidth} allData={[]} mb={"mb-4"}>

                {/* その日そのメイン案件におけるセットが「keyValueSets」で、それをmapごとにわけ、それを町目ごとに見ていく */}

                 {Object.entries(dataInEachMainProject).map(function([mapNumber,eachDataByMap],trIndex){
                    return(
                        Object.values(eachDataByMap).map((eachData,indexWithMaps)=>
                            // テーブルの中身
                            <TbodyInner key={`${trIndex}_${indexWithMaps}`} {...{mainProjectName,projectSets,eachData,mapNumber,trIndex,indexWithMaps,widthSets,onAssignedInputChange,inputRefs,inputValues,isConfirm}}/>
                      )
                    )
                 })
                }
                {/* 合計 */}
                <TrForSum {...{inputValues,mainProjectName,projectSets,widthSets}} />
                </BaseTable>
                </React.Fragment>
            )})

    )

}
