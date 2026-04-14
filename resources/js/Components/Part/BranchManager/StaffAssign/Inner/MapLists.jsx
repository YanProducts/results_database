import SelectParts from "../../../../Common/SelectParts";
import React from "react";

// スタッフの割り当てをMapNumberから選ぶ場合
export default function MapLists({projectsAndTowns,selectedMainProject,mapMeta,staffs,handleAssignChangeInMaps,maxWidth="max-w-80", minWidth="min-w-72.5"}){

    // メイン案件が選択されていないとき
    if(!selectedMainProject){
          return<div className={`text-center base_frame base_backColor ${minWidth} ${maxWidth} mb-3`}><p>メイン案件を選択してください</p></div>
    }

    // map番号を重複なしで取得し、昇順に並べる
    const mapDuplicatedNumberLists=(projectsAndTowns[selectedMainProject].each_sets).map(eachSet=>eachSet.map_number);
    const mapNumberLists=([...new Set(mapDuplicatedNumberLists)]).sort((a,b)=>a-b);

    return(
        <div className="mb-3">
        {
        mapNumberLists.map(mapNumber=>
            <div className={`flex items-center base_frame ${minWidth} ${maxWidth} mx-auto my-0 border border-black base_backColor text-center h-9`} key={mapNumber}>
                {/* スタッフの選択 */}
                <SelectParts name="mapStaffs" value={ mapMeta?.[selectedMainProject]?.[mapNumber]?.staffId || "" } onChange={(e)=>handleAssignChangeInMaps(e,mapNumber)} prefix={mapNumber + "："} prefixPercent="w-[20%]" selectPercent="w-[75%]" prefixMinWidth="min-w-10" selectMinWidth="min-w-40" keyValueSets={staffs} allowEmptyOption={false} />
            </div>
        )}
        </div>
    )
}
