import { Fragment } from "react/jsx-runtime";

// 案件一覧にいて、どの内容を表示しないかのリスト
export default function HiddenList({columnForHiddenLists,onHiddenListsChange,hiddenListVisible,hiddenPatterns,allHiddenLists,onShowFullDataChange,onEachHiddenCloseClick}){

    return(
      <div id="popUpHiddenLists"
      className={`${hiddenListVisible ? "block" : "hidden"} absolute w-30 h-auto bg-white border border-black`}>
        {
        columnForHiddenLists &&
        <>
        {hiddenPatterns[columnForHiddenLists].map((eachPtn,index)=>
           <Fragment key={index}>
             <input id={`${eachPtn[0]}`} type="checkbox" checked={!allHiddenLists[columnForHiddenLists].includes(eachPtn[0])} onChange={(e)=>onHiddenListsChange(e,columnForHiddenLists,eachPtn[0])}/>
             <label htmlFor={`${eachPtn[0]}`}>{eachPtn[1]}</label>
             <br/>
            </Fragment>
        )}
        {/* そのカラムの全表示にチェックがついている場合 */}
            <span className={`inline-block w-full cursor-pointer bg-lime-300 text-center border-t border-black`} onClick={(e)=>onShowFullDataChange(e,columnForHiddenLists)}>
            全表示</span>
            <br/>
        {/* 閉じるボタン */}
            <div className="text-center font-bold underline-offset-2 cursor-pointer bg-yellow-100 border-t border-black" onClick={onEachHiddenCloseClick}>閉じる</div>
        </>
        }

    </div>
  )
}
