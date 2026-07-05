import React from "react";
import BaseTable from "../../../Common/BaseTable";
import TbodyInner from "./TbodyInner";
import TrForSum from "./TrForSum";
import IssuedAndReturns from "./IssueAndReturns";

// 報告書テーブルの内部
export default function ReportInner({pageMinWidth,pageMaxWidth,issuedCount,returnedCount,onIssuedOrReturnedCountsChange,setIssuedCount,setReturnedCount,onAssignedInputChange,inputRefs,inputValues,onInputKeyDown,tableSets,isConfirm,processing,fromSimpleFlag}){

    return(
         tableSets.map(function(eachTableSets,index){
                // プロジェクトの数に応じてthやtdの長さの変化
                const widthSets=eachTableSets.widthSets
                const mainProjectName=eachTableSets.mainProjectName;
                const projectSets=eachTableSets.projectSets;

                return(
                <React.Fragment key={index}>

                <BaseTable tableTheme={mainProjectName} width={"w-[97.5%]"} thSets={{"town":"町名","household":"世帯数",...projectSets,"mapNumber":"地図番号"}} thWidthSets={widthSets} maxWidth={pageMaxWidth} minWidth={pageMinWidth} allData={[]} mb={"mb-4"}>

                    {/* 持ち出しと返却(確認用) */}
                    <IssuedAndReturns {...{projectSets,mainProjectName,onIssuedOrReturnedCountsChange,state:issuedCount,setState:setIssuedCount,isConfirm,processing,jpnWord:"持ち出し"}}/>
                    <IssuedAndReturns {...{projectSets,mainProjectName,onIssuedOrReturnedCountsChange,state:returnedCount,setState:setReturnedCount,isConfirm,processing,jpnWord:"返却"}}/>


                {/* その日そのメイン案件におけるセットが「keyValueSets」で、それをmapごとにわけ、それを町目ごとに見ていく */}

                    {Object.entries(eachTableSets.dataInEachMainProject).map(function([mapNumber,eachDataByMap],trIndex){
                    return(
                        Object.values(eachDataByMap).map((eachData,indexInMaps)=>
                            // テーブルの中身
                            <TbodyInner key={`${trIndex}_${indexInMaps}`} {...{mainProjectName,projectSets,eachData,mapNumber,trIndex,indexInMaps,widthSets,onAssignedInputChange,inputRefs,inputValues,onInputKeyDown,isConfirm,fromSimpleFlag,processing}}/>
                        )
                    )
                    })
                }

                {/* 合計(町目ごと＆そのずれ) */}
                <TrForSum {...{projectSets,isConfirm,sumSets:eachTableSets.sumSets}} />

                </BaseTable>
    </React.Fragment>
    )}))
}
