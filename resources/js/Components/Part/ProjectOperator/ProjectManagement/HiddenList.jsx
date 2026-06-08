import { all } from "axios";
import getJpnWord from "../../../../Support/Common/getJpnWord";

// 案件一覧にいて、どの内容を表示しないかのリスト
export default function HiddenList({columnForHiddenLists,onHiddenListsChange,hiddenListVisible,hiddenPatterns,allHiddenLists}){

    console.log(hiddenPatterns?.[columnForHiddenLists])

  return(
    columnForHiddenLists &&
    <div className={`${hiddenListVisible ? "block" : "hidden"} w-3 h-auto`}>
        {Object.entries(hiddenPatterns[columnForHiddenLists]).map(eachPtn=>
            <>
             <input id={`${eachPtn[0]}`} type="checkbox" checked={`${!allHiddenLists[columnForHiddenLists].includes(eachPtn[0])}`} onChange={(e)=>onHiddenListsChange(e,columnForHiddenLists,eachPtn[0])}/>
             <label htmlFor={`${eachPtn[0]}`}>{eachPtn[1]}</label>
            </>
        )}
    </div>
  )
}
