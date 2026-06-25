import SubmitOrBackButtons from "../../../Common/SubmitOrBackButtons";
import BasePageHeader from "../../../Common/BasePageHeader";
import BaseTable from "../../../Common/BaseTable";
import SimpleChoicedMapListsForView from "../Common/SimpleChoicedMapListsForView";
import ViewValidationErrors from "../../../Common/ViewValidationErrors";
import simpleGetProjectNameForView from "../../../../Support/BranchManager/simpleGetProjectNameForView";


// スタッフを割り当てた後のデータ確認ページ
export default function DataConfirmForSimple({what,type,errors,pageMinWidth,pageMaxWidth,processing,selectedDate,staffs,choicedMap,choicedByProjects,onConfirmOkClick,onConfirmCancelClick}){

    const staffsInSelectedDate=staffs[selectedDate]

    return(
    <div className={`base_frame ${pageMinWidth} ${pageMaxWidth}`}>

        <BasePageHeader {...{what,type,pageMaxWidth,pageMinWidth,subtitle:"以下でよろしいですか？"}}/>

        {/* バリデーションエラー */}
        <ViewValidationErrors errors={errors}/>

        <BaseTable tableTheme={`スタッフごとの表-日付：${new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"})}`}
        thSets={{"staff":"スタッフ名","confirm":"選択マップ"}}
        width="w-[90%]" thWidthSets={["w-[30%]","w-[70%]"]}
        pageMaxWidth={pageMaxWidth} pageMinWidth={pageMinWidth}>
            {Object.values(staffsInSelectedDate).map((eachStaff,index)=>
                // スタッフごとに列を並べる
                <tr className="border-2 border-black" key={index}>
                        {/* スタッフ名 */}
                        <td  className={`border-2 border-black w-[20%] align-middle text-center base_backColor"}`}>{eachStaff}</td>
                        <td className="w-[80%]">
                          <SimpleChoicedMapListsForView {...{eachStaff,choicedMap,selectedDate,needBr:false}}/>
                        </td>
                </tr>
            )}
        </BaseTable>

        <p>　</p>

        <BaseTable tableTheme={`案件ごとの表-日付：${new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"})}`}
        thSets={{"projectName":"案件名","maps":"地図","staff":"スタッフ名"}}
        width="w-[90%]" thWidthSets={["w-[30%]","w-[30%]","w-[40%]"]}
        pageMaxWidth={pageMaxWidth} pageMinWidth={pageMinWidth}>
            {Object.entries(choicedByProjects).map(([projectName,valueInProjectName],projectNameIndex)=>
                Object.entries(valueInProjectName).map(([roundNumber,valueInRoundNumber],roundNumberIndex)=>
                    Object.entries(valueInRoundNumber).map(([mapNumber,staffsHavingMapNumber],mapNumberIndex)=>
                        // 案件ごとに列を並べる
                        <tr className="border-2 border-black" key={`${projectNameIndex}_${roundNumberIndex}_${mapNumberIndex}`}>
                            <td className="border border-black">{simpleGetProjectNameForView({valueInProjectName,projectName,roundNumberIndex})}</td>
                            <td className="border border-black">{mapNumber}</td>
                            <td className="border border-black">{staffsHavingMapNumber.join("、")}</td>
                        </tr>
                    )))
              }
        </BaseTable>

        <p>　</p>

        {/* 提出or戻る */}
        <SubmitOrBackButtons minWidth={pageMinWidth} maxWidth={pageMaxWidth} processing={processing} errors={errors} onSubmitBtnClick={onConfirmOkClick} onCancelBtnClick={onConfirmCancelClick}/>

    </div>
    )
}
