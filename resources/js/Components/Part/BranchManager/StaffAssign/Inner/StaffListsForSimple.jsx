import React from "react";
import PopUpForMultiChoice from "../../../../Common/PopUpForMultiChoice";
import BaseTable from "../../../../Common/BaseTable";
import { list } from "postcss";

export default function StaffListsForSimple({selectedDate,pageMinWidth,pageMaxWidth,staffsInSelectedDate,planIdsAndMapsByMainProjects,projectNameInTheDay,staffInChoice,popUpVisible,onMapChoiceClick,onMapDecide,choicedMap}){
        // popUp用にリストを作り替える(プロジェクト名=>mapのNumber)
        const listForPopUp=Object.fromEntries(projectNameInTheDay.map(eachProjectSets=>
            // round_numberを渡すかどうか
            [
            eachProjectSets.project_name,
                Object.values(planIdsAndMapsByMainProjects[eachProjectSets.project_name][eachProjectSets.round_number].map_plan_data).map(eachSet=>eachSet.map_number)
        ]))

      return(
        <>
        <BaseTable tableTheme={`日付：${new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"})}`}
        thSets={{"staff":"スタッフ名","edit":"マップを選ぶ","confirm":"マップの確認"}} pageMaxWidth={pageMaxWidth} pageMinWidth={pageMinWidth}>
            {Object.values(staffsInSelectedDate).map((eachStaff,index)=>
                // スタッフごとに列を並べる
                <tr className="border-2 border-black" key={index}>
                        {/* スタッフ名 */}
                        <td  className="border-2 border-black base_backColor w-[30%] align-middle text-center">{eachStaff}</td>
                        {/* プロジェクト名とmapの選択(popUp) */}
                        <td className="border-2 border-black base_backColor w-[40%] align-middle text-center">
                            <span className="cursor-pointer" onClick={(e)=>{onMapChoiceClick(e,eachStaff)}}>選択してください</span>
                        </td>

                        <td>
                            確認
                        </td>

                     </tr>
            )}
        </BaseTable>

        {/* クリックイベントで動的に発動 */}
        <PopUpForMultiChoice lists={listForPopUp} valueA={staffInChoice} clickEvent={(e,projectName,mapNumber,choicedStaff)=>{onMapDecide(choicedStaff,projectName,mapNumber)}} checkState={choicedMap[selectedDate]} {...{popUpVisible}}/>

        </>
        )
}
