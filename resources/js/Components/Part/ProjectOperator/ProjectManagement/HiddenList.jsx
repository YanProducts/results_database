import getJpnWord from "../../../../Support/Common/getJpnWord";

// 案件一覧にいて、どの内容を表示しないかのリスト
export default function HiddenList({columnForHiddenList,onHiddenListsChange,hiddenPattern}){
  return(
    columnForHiddenList &&
    <div className="hidden w-3 h-auto">
        {hiddenPattern[columnForHiddenList].map(eachPtn=>
            <>
             <input id={`${eachPtn}`} type="checkbox" onChange={(e)=>onHiddenListsChange(e,columnForHiddenList,eachPtn)}/>
             <label htmlFor={`${eachPtn}`}>{getJpnWord(eachPtn)}</label>
            </>
        )}
    </div>
  )
}
