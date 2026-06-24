import React from "react";

// スタッフの欄のクリック時に表示される地図番号のリストをpopUp
// 複数選択の時のpopUp(divで表示して、checkBoxに格納し、React連動)

export default function PopUpForMapChoiceByStaff({planIdsAndMapsByMainProjects,popUpVisible,staffInChoice,choicedMap,selectedDate,onMapDecide,onMapChoiceClose}){

    // planIdsAndMapsByMainProjectsはprojectName=>roundNumber=>{each_sets:全データ,map_plan_data:地図とプランIdのデータ}

    return(
            <div id="popUpForMultiSort" className={`${popUpVisible ? "block" :"hidden"} absolute z-20 w-30`}>
                {
                Object.entries(planIdsAndMapsByMainProjects).map(([projectName,valueInProjectName])=>
                    Object.entries(valueInProjectName).map(function([roundNumber,eachSets],roundNumberIndex){

                    // そのプロジェクトがその回において1度ならroundNumberは表示しない
                    const projectNameForView=valueInProjectName.length==1 ? projectName : projectName + ":" + (roundNumberIndex + 1);
                    return(
                        // プロジェクト名
                        <div className="font-bold first:border-t border-black" key={`${projectName}_${roundNumber}`}>
                            <div className="bg-white border text-center  border-black p-1 border-b">{projectNameForView}</div>
                                {Object.values(eachSets.map_plan_data).map(function(eachDataSets){
                                    const mapNumber=eachDataSets.map_number;
                                    return(
                                    <div key={`${projectName}_${roundNumber}_${mapNumber}`} className="flex items-center justify-center border-x border-b  border-black bg-amber-100 hover:bg-lime-200" >
                                        <input className="hidden" type="checkbox" multiple id={`${projectName}_${roundNumber}_${mapNumber}`} name={`${staffInChoice}_${projectNameForView}[]`}
                                       value={mapNumber}
                                       onChange={(e)=>{onMapDecide(e,staffInChoice,projectName,roundNumber,mapNumber)}} checked={`${choicedMap?.[selectedDate]?.[staffInChoice]?.[projectName]?.[roundNumber]?.[mapNumber]}`}></input>
                                        {/* 背景色は動的に変更するように後で設定 */}
                                        <label className="text-center  cursor-pointer" htmlFor={`${projectName}_${roundNumber}_${mapNumber}`}>{`map${mapNumber}`}</label>
                                    </div>
                                    )
                                }
                                )} {/* eachSetsのmap */}
                            {/* 選択 */}
                        </div>
                    )
                }) //roundNumberのmap
                ) //projectNameのmap
                }
                <div className={`flex items-center justify-center border-x border-b  border-black bg-sky-200 hover:bg-red-200 cursor-pointer`} onClick={onMapChoiceClose} >閉じる</div>
             <p>　</p>
            </div>
    )
}
