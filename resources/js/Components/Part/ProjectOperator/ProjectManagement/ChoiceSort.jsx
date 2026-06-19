import getJpnWord from "../../../../Support/Common/getJpnWord"
// 何でsoretするか
export default function ChoiceSort({maxWidth="",minWidth="",overViewItems,prioritySort,selectedSort,ascOrDes,selectedAscOrDes,onAscOrDesClick,onSortChangeClick,onSortKindChange,onSortChangeClose,onSortChangeDecide,sortItemIsVisible,columnForHiddenLists}){

    return(
        <>
        {/* 確認部分 */}
            <div className={`base_frame ${minWidth} ${maxWidth} base_backColor flex text-left pl-3 mb-2`}>
            <p className="font-bold">現在の並び：{overViewItems[prioritySort]}で{getJpnWord(ascOrDes)}</p><span className={`font-bold ml-3 px-2 inline-block underline-offset-2 ${columnForHiddenLists ? "opacity-30" : "cursor-pointer opacity-100"} bg-orange-100 border border-b`} onClick={columnForHiddenLists ? ()=>{} :(e)=>onSortChangeClick(e)}>変更</span>
            </div>
        {/* 変更部分 */}
            <div id="popUpSortLists" className={`${sortItemIsVisible ? "block":"hidden"} absolute bg-green-300 border border-black rounded-sm z-10`}>

            {/* 何でソートするか */}
            <div className="py-1">
                {
                    Object.entries(overViewItems).map(eachItem=>
                        <div key={`${eachItem[0]}`}><label htmlFor={`sort_ ${eachItem[0]}`}><input name="sortRagio" type="radio" id={`sort_${eachItem[0]}`} value={`${eachItem[0]}`} onChange={onSortKindChange} checked={selectedSort == eachItem[0]}/>{eachItem[1]}</label></div>
                    )
                }
            </div>

            {/* 昇順か降順か */}
            <div key="asc" className="border-t border-dashed pt-1 border-black"><label htmlFor="asc"><input name="sortAscOrDes" type="radio" onChange={onAscOrDesClick} checked={selectedAscOrDes=="asc"} id="asc" value="asc" />{getJpnWord("asc")}</label></div>
            <div key="des" className="pb-1"><label htmlFor="des"><input name="sortAscOrDes" type="radio" onChange={onAscOrDesClick} checked={selectedAscOrDes=="des"} id="des" value="des" />{getJpnWord("des")}</label></div>

            {/* 決定か閉じる */}
            <div key="decide" className="bg-amber-200 text-center border-y border-black"><span className="cursor-pointer font-bold" onClick={onSortChangeDecide}>決定</span></div>
            <div key="close" className="bg-amber-200 text-center"><span className="cursor-pointer font-bold" onClick={onSortChangeClose}>閉じる</span></div>
            </div>
        </>
    )
}
