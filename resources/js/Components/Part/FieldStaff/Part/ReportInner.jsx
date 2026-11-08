import React from "react";
import BaseTable from "../../../Common/BaseTable";
import TrInner from "./TrInner";
import TrForSum from "./TrForSum";
import IssuedAndReturns from "./IssueAndReturns";
import WriteReportContext from "../../../../Contexts/FieldStaffs/useWriteReportContexts";
import SelectPartsForViewChange from "../../../Common/SelectPartsForViewChange";
import FlatToggleLists from "../../../Common/FlatToggleLists";
import ChoiceMapNumberWhenProjectOnly from "./ChoiceMapNumberWhenProjectOnly";

// 報告書テーブルの内部
export default function ReportInner({pageMinWidth,pageMaxWidth,issuedCount,returnedCount,onIssuedOrReturnedCountsChange,setIssuedCount,setReturnedCount,onAssignedInputChange,inputRefs,inputValues,onInputKeyDown,tableSets,isConfirm,processing,fromSimpleFlag,changedData,selectedDate}){

    const {isBigMedia,projectOnlyAssignedDates,choiceFinishedMapProjectLists}=React.useContext(WriteReportContext);

    return(
        // 案件が届いていない場合は外側で捕捉済み
         tableSets.map(function(eachTableSets,index){
                // プロジェクトの数に応じてthやtdの長さの変化
                const widthSets=eachTableSets.widthSets
                const mainProjectName=eachTableSets.mainProjectName;
                const projectSets=eachTableSets.projectSets;
                const dataInEachMainProject=eachTableSets.dataInEachMainProject;

                return(
                <React.Fragment key={index}>

                <BaseTable tableTheme={mainProjectName} width={"w-[97.5%]"} thSets={{"town":"町名","household":"世帯数",...projectSets,"mapNumber":(isBigMedia ? "地図" : "") + "番号"}} thWidthSets={widthSets} maxWidth={pageMaxWidth} minWidth={pageMinWidth} allData={[]} mb={"mb-4"} thHidden={projectOnlyAssignedDates.includes(selectedDate) && !choiceFinishedMapProjectLists.includes(mainProjectName)}>

                {/* 案件のみ版のデータがこの日に存在するときはMap選択 */}
                {projectOnlyAssignedDates.includes(selectedDate) &&
                <ChoiceMapNumberWhenProjectOnly {...{projectSets,mainProjectName,dataInEachMainProject,pageMaxWidth,pageMinWidth}}/>
                }

                    {/* 持ち出しと返却(確認用) */}
                    { choiceFinishedMapProjectLists.includes(mainProjectName) &&
                    <>
                        <IssuedAndReturns {...{projectSets,mainProjectName,onIssuedOrReturnedCountsChange,state:issuedCount,setState:setIssuedCount,isConfirm,processing,jpnWord:"持ち出し"}}/>
                        <IssuedAndReturns {...{projectSets,mainProjectName,onIssuedOrReturnedCountsChange,state:returnedCount,setState:setReturnedCount,isConfirm,processing,jpnWord:"返却"}}/>

                        {/* その日そのメイン案件におけるセットが「keyValueSets」で、それをmapごとにわけ、それを町目ごとに見ていく */}
                        {Object.entries(dataInEachMainProject).map(function([mapNumber,eachDataByMap],trIndex){
                        return(
                            Object.values(eachDataByMap).map((eachData,indexInMaps)=>
                                // テーブルの中身
                                <TrInner key={`${trIndex}_${indexInMaps}`} {...{mainProjectName,projectSets,eachData,mapNumber,trIndex,indexInMaps,widthSets,onAssignedInputChange,inputRefs,inputValues,onInputKeyDown,isConfirm,fromSimpleFlag,processing,changedData}}/>
                            )
                        )
                        })
                    }

                    {/* 合計(町目ごと＆そのずれ) */}
                    <TrForSum {...{mainProjectName,projectSets,isConfirm,sumSets:eachTableSets.sumSets,changedData}} />
                 </>
                }

                </BaseTable>
    </React.Fragment>
    )})
 )
}
