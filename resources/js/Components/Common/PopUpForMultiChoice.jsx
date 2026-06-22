import React from "react";

// 複数選択の時のpopUp(divで表示して、checkBoxに格納し、React連動)
// listsはkeyが文字列でvalueは配列のオブジェクトである必要あり
export default function PopUpForMultiChoice({popUpVisible,lists,valueA,valueB="",valueC="",clickEvent,checkState}){
    return(
            <div id="popUpForMultiSort" className={`${popUpVisible ? "block" :"hidden"} absolute z-20`}>
                {
                Object.entries(lists).map(([key,value])=>

                    <React.Fragment key={key}>
                        {/* 表示 */}
                        <div>{key}</div>
                            {value.map((eachValue,index)=>
                                <React.Fragment key={`${key}_${index}`}>
                                    <input className="hidden" type="checkbox" multiple id={`{${key}_${index}}`} name={`${key}[]`} onChange={(e)=>{clickEvent(e,key,eachValue,valueA,valueB,valueC)}} checked={`${checkState[key][index]}`}></input>
                                    {/* 背景色は動的に変更するように後で設定 */}
                                    <label className="flex items-center cursor-pointer hover:bg-lime-200" htmlFor={`{${key}_${index}}`}>{`map${eachValue.map_number}`}</label>
                                </React.Fragment>
                            )}
                        {/* 選択 */}

                    </React.Fragment>

                )}
            </div>
    )
}
