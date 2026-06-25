import React from "react";
import PopUpForMapChoiceByStaff from "./PopUpForMapChoiceByStaff";
import BaseTable from "../../../../Common/BaseTable";
import SimpleChoicedMapListsForView from "../../Common/SimpleChoicedMapListsForView";

export default function StaffListsForSimple({selectedDate,pageMinWidth,pageMaxWidth,staffsInSelectedDate,planIdsAndMapsByMainProjects,staffInChoice,popUpVisible,onMapChoiceClick,onMapDecide,onMapChoiceClose,choicedMap,onClickDateReset}){

      return(
        <>
        <BaseTable tableTheme={`日付：${new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"})}`}
        thSets={{"staff":"スタッフ名","edit":"マップを選ぶ","confirm":"マップの確認"}}
        width="w-[85%]" thWidthSets={["25%","35%","40%"]}
        pageMaxWidth={pageMaxWidth} pageMinWidth={pageMinWidth}>
            {Object.values(staffsInSelectedDate).map((eachStaff,index)=>
                // スタッフごとに列を並べる
                <tr className="border-2 border-black" key={index}>
                        {/* スタッフ名 */}
                        <td  className={`border-2 border-black w-[25%] align-middle text-center ${staffInChoice == eachStaff ? "bg-green-300" : "base_backColor"}`}>{eachStaff}</td>
                        {/* プロジェクト名とmapの選択(popUp) */}
                        <td className="border-2 border-black base_backColor w-[35%] align-middle text-center">
                            <span className={`${popUpVisible ? "opacity-30" : "cursor-pointer opacity-100"} inline-block w-full`} onClick={popUpVisible ? ()=>{} : (e)=>{onMapChoiceClick(e,eachStaff)}}>選択してください</span>
                        </td>
                        <td className="whitespace-pre-wrap w-[40%]">
                            <SimpleChoicedMapListsForView {...{eachStaff,choicedMap,selectedDate}}/>
                        </td>
                     </tr>
                  )
            }
        </BaseTable>

        <div className={`base_frame base_backColor text-center  max-w-150 mt-2 border-black border rounded-sm`}>日程を選択し直す場合は<span className={`text-blue-600 font-bold  ${popUpVisible ? "cursor-auto opacity-30" :"cursor-pointer underline underline-offset-4 opacity-100"}`} onClick={popUpVisible ? ()=>{} :onClickDateReset}>こちら</span></div>

        <p>　</p>

        {/* クリックイベントで動的に発動 */}
        <PopUpForMapChoiceByStaff {...{planIdsAndMapsByMainProjects,popUpVisible,staffInChoice,choicedMap,selectedDate,onMapDecide,onMapChoiceClose}}/>

        </>
        )
}
