import SelectParts from "../../../Common/SelectParts";
import React from "react";

// スタッフの割り当てをMapNumberから選ぶ場合
export default function MapLists({projectsAndTowns,selectedMainProject,staffs,handleAssignChangeInMaps,prefixPercent="w-[30%]",maxWidth="max-w-80", minWidth="min-w-72.5"}){


    // メイン案件が選択されていないとき
    if(!selectedMainProject){
        return<div className="text-center"><p>メイン案件を選択してください</p></div>
    }


    // map番号を重複なしで取得
    const mapDuplicatedNumberLists=projectsAndTowns[selectedMainProject].map(each_set=>each_set.map_number);
    const mapNumberLists=[...new Set(...mapDuplicatedNumberLists)]

    return(
        mapNumberLists.map(mapNumber=>
            <div lassName={`flex items-center base_frame ${minWidth} ${maxWidth} mx-auto my-3`} key={mapNumber}>
                <span className={`inline-block ${prefixPercent} min-w-32 text-right`}>{mapNumber}</span>
                {/* スタッフの選択 */}
                <SelectParts name="mapStaffs" value={selectedMapNumber[selectedMainProject][mapNumber]} onChange={(e,mapNumber)=>handleAssignChangeInMaps(e,mapNumber)} prefix={mapNumber} keyValueSets={staffs} allowEmptyOption={false} />
            </div>
        )
    )
}
