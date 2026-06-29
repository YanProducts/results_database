import BaseTable from "../../../../../Common/BaseTable";
import SimpleChoicedMapListsForView from "../../../Common/SimpleChoicedMapListsForView";

// 確認の際の地図番号版での案件ごとに区分けしたテーブル
export default function TableByStaffsForConfirm({selectedDate,pageMaxWidth,pageMinWidth,staffsInSelectedDate,choicedMap}){
    return(
        <BaseTable tableTheme={`スタッフごとの表-日付：${new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"})}`}
        thSets={{"staff":"スタッフ名","confirm":"選択マップ"}}
        width="w-[90%]" thWidthSets={["w-[30%]","w-[70%]"]}
        pageMaxWidth={pageMaxWidth} pageMinWidth={pageMinWidth}>
            {Object.values(staffsInSelectedDate).map((eachStaff,index)=>
                // スタッフごとに列を並べる
                <tr className="border-2 border-black" key={index}>
                        {/* スタッフ名 */}
                        <td  className={`border-2 border-black w-[20%] align-middle text-center base_backColor"}`}>{eachStaff}</td>
                        {/* 地図番号 */}
                        <td className="w-[80%]">
                          <SimpleChoicedMapListsForView {...{eachStaff,choicedMap,selectedDate,needBr:false}}/>
                        </td>
                </tr>
            )}
        </BaseTable>
    )
}
