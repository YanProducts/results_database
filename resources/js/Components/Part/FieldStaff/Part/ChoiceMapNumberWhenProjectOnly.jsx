import React from "react";
import WriteReportContext from "../../../../Contexts/FieldStaffs/useWriteReportContexts";
import FlatToggleLists from "../../../Common/FlatToggleLists";

//  projectOnlyで案件選択行われている日の場合は地図番号の選択
export default function ChoiceMapNumberWhenProjectOnly({projectSets,mainProjectName,dataInEachMainProject,pageMaxWidth,pageMinWidth}){

    // 変数呼び出し
    const {choicedMap,onMapNumberChange,onMapNumberChoiceDecide,onMapNumberChoiceReset,choiceFinishedMapProjectLists}=React.useContext(WriteReportContext);

    const choicedMapExistsInTheProjects=(Object.keys(choicedMap).includes(mainProjectName) && Object.keys(choicedMap[mainProjectName]).length>0);

    return(
             <tr>
                <td colSpan={ 3 + Object.keys(projectSets).length}>
                    {!choiceFinishedMapProjectLists.includes(mainProjectName) ?
                    <>
                        <p className="base_frame mt-3 mb-0 text-center font-bold base_backColor max-w-20 border border-black rounded-sm">地図を選択してください</p>
                        <FlatToggleLists {...{pageMaxWidth,pageMinWidth,      prefixId:mainProjectName,contents:Object.keys(dataInEachMainProject).map((eachMapNumber)=>({id:eachMapNumber,
                        nameForUI:"Map"+eachMapNumber})),formLists:choicedMap[mainProjectName] ?? [], formNotNumberFlag:true,onToggleListsChange:(e)=>{onMapNumberChange({e,mainProjectName})}}}//メソッドの引数は引き渡した先から渡される
                    />
                    <div className="flex justify-center my-3"><button className={`base_btn ${choicedMapExistsInTheProjects ? "active_btn" : "non_active_btn"}`} onClick={()=>{onMapNumberChoiceDecide(mainProjectName)}} disabled={!choicedMapExistsInTheProjects} >決定！</button></div>
                    </>
                    :
                    <div className="base_frame flex justify-center align-middle mx-auto my-3">
                        {/* 地図表示(地図番号) */}
                        <div className="bg-amber-300 text-center border border-black rounded-md p-2 mr-2 font-bold">選択中の地図：{choicedMap[mainProjectName].map(eachMapNumber=>eachMapNumber.replace(/[0-9]/g, n=>String.fromCharCode(n.charCodeAt(0) + 65248))).join("・")}</div>
                        <div className="ml-2"><button className="border border-black base_btn text-blue-700 underline underline-offset-4 font-bold" onClick={()=>{onMapNumberChoiceReset(mainProjectName)}}>地図を変更</button></div>
                    </div>
                    }
                </td>
            </tr>
    )
}
