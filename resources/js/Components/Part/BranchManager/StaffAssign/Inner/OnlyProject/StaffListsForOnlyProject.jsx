import React from "react";
import PopUpForProjectChoiceByStaff from "./PopUpForProjectChoiceByStaff";
import BaseTable from "../../../../../Common/BaseTable";
import ChoicedProjectListsForView from "./ChoicedProjectListsForView";


export default function StaffListsForOnlyProject({staffsInSelectedDate,projectSetsInTheDay,selectedDate,pageMinWidth,pageMaxWidth,staffInChoice,popUpVisible,onProjectChoiceClick,onProjectDecide,onProjectChoiceClose,choicedProject,onClickDateReset,dateProjectsIndex}){

      return(
        <>
        <BaseTable tableTheme={`日付：${new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"})}`}
        thSets={{"staff":"スタッフ名","confirm":"マップの確認"}}
        width="w-[85%]" thWidthSets={["w-[35%]","w-[65%]"]}
        pageMaxWidth={pageMaxWidth} pageMinWidth={pageMinWidth}>
            {Object.values(staffsInSelectedDate).map((eachStaff,index)=>
                // スタッフごとに列を並べる
                <tr className="border-2 border-black" key={index}>
                        {/* スタッフ名 */}
                        {/* クリックしたらpopUpが表示 */}
                        <td  className={`border-2 border-black w-[35%] align-middle text-center ${staffInChoice == eachStaff ? "bg-green-300" : "base_backColor"} ${popUpVisible ? "cursor-auto" : "cursor-pointer"}`} onClick={popUpVisible ? ()=>{} : (e)=>{onProjectChoiceClick(e,eachStaff)}}>{eachStaff}</td>

                        {/* 現在選択中の案件 */}
                        <td className="whitespace-pre-wrap w-[65%]">
                            <ChoicedProjectListsForView {...{eachStaff,choicedProject,selectedDate,projectSetsInTheDay}}/>
                        </td>


                     </tr>
                  )
            }
        </BaseTable>

        <div className={`base_frame base_backColor text-center  max-w-150 mt-2 border-black border rounded-sm`}>日程を選択し直す場合は<span className={`text-blue-600 font-bold  ${popUpVisible ? "cursor-auto opacity-30" :"cursor-pointer underline underline-offset-4 opacity-100"}`} onClick={popUpVisible ? ()=>{} :onClickDateReset}>こちら</span></div>


        {/* クリックイベントで動的に発動 */}
        <PopUpForProjectChoiceByStaff {...{popUpVisible,staffInChoice,choicedProject,selectedDate,onProjectDecide,onProjectChoiceClose,dateProjectsIndex}}/>

        </>

    )
}
