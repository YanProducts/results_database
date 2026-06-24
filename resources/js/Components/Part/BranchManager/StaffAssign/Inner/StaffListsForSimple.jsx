import PopUpForMapChoiceByStaff from "./PopUpForMapChoiceByStaff";
import BaseTable from "../../../../Common/BaseTable";

export default function StaffListsForSimple({selectedDate,pageMinWidth,pageMaxWidth,staffsInSelectedDate,planIdsAndMapsByMainProjects,staffInChoice,popUpVisible,onMapChoiceClick,onMapDecide,onMapChoiceClose,choicedMap}){

      return(
        <>
        <BaseTable tableTheme={`日付：${new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"})}`}
        thSets={{"staff":"スタッフ名","edit":"マップを選ぶ","confirm":"マップの確認"}} pageMaxWidth={pageMaxWidth} pageMinWidth={pageMinWidth}>
            {Object.values(staffsInSelectedDate).map((eachStaff,index)=>
                // スタッフごとに列を並べる
                <tr className="border-2 border-black" key={index}>
                        {/* スタッフ名 */}
                        <td  className={`border-2 border-black w-[30%] align-middle text-center ${staffInChoice == eachStaff ? "bg-green-300" : "base_backColor"}`}>{eachStaff}</td>
                        {/* プロジェクト名とmapの選択(popUp) */}
                        <td className="border-2 border-black base_backColor w-[40%] align-middle text-center">
                            <span className={`${popUpVisible ? "opacity-30" : "cursor-pointer opacity-100"}`} onClick={popUpVisible ? ()=>{} : (e)=>{onMapChoiceClick(e,eachStaff)}}>選択してください</span>
                        </td>

                        <td>
                            確認
                        </td>

                     </tr>
            )}
        </BaseTable>

        {/* クリックイベントで動的に発動 */}
        <PopUpForMapChoiceByStaff {...{planIdsAndMapsByMainProjects,popUpVisible,staffInChoice,choicedMap,selectedDate,onMapDecide,onMapChoiceClose}}/>

        </>
        )
}
