import BaseTable from "../../../../../Common/BaseTable";
// 確認の際の地図番号版での案件ごとに区分けしたテーブル
export default function TableByProjectsForConfirm({selectedDate,pageMaxWidth,pageMinWidth,choicedByProjects,simpleGetProjectNameForView}){
    return(
       <BaseTable tableTheme={`案件ごとの表-日付：${new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"})}`}
        thSets={{"projectName":"案件名","maps":"地図","staff":"スタッフ名"}}
        width="w-[90%]" thWidthSets={["w-[30%]","w-[30%]","w-[40%]"]}
        pageMaxWidth={pageMaxWidth} pageMinWidth={pageMinWidth}>
            {Object.entries(choicedByProjects).map(([projectName,valueInProjectName],projectNameIndex)=>
                Object.entries(valueInProjectName).map(([roundNumber,valueInRoundNumber],roundNumberIndex)=>
                    Object.entries(valueInRoundNumber).map(([mapNumber,staffsHavingMapNumber],mapNumberIndex)=>
                        // 案件ごとに列を並べる
                        <tr className="border-2 border-black" key={`${projectNameIndex}_${roundNumberIndex}_${mapNumberIndex}`}>
                            <td className="border border-black border-2">{simpleGetProjectNameForView({valueInProjectName,projectName,roundNumberIndex})}</td>
                            <td className="border border-black border-2">{mapNumber}</td>
                            <td className="border border-black">{staffsHavingMapNumber.join("、")}</td>
                        </tr>
                    )))
              }
        </BaseTable>
    )
}
