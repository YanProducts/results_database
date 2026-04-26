import SelectParts from "../../../../Common/SelectParts";
import React from "react";

// スタッフの割り当てをMapNumberから選ぶ場合
export default function MapLists({projectsAndTowns,selectedDate,selectedMainProject,mapMeta,staffs,handleAssignChangeInMaps,maxWidth="max-w-80", minWidth="min-w-72.5"}){

    // メイン案件が選択されていないとき
    if(!selectedMainProject){
        return<div className={`text-center base_frame base_backColor ${minWidth} ${maxWidth} mb-3`}><p>メイン案件を選択してください</p></div>
    }

    // メイン案件の情報。何度か使うので最初に取得
    const mainSets=projectsAndTowns[selectedMainProject].each_sets;

    // map番号を重複なしで取得し、昇順に並べる
    const mapDuplicatedNumberLists=(mainSets).map(eachSet=>eachSet.map_number);
    const mapNumberLists=([...new Set(mapDuplicatedNumberLists)]).sort((a,b)=>a-b);

    return(
        <div className="mb-3">
        {
        mapNumberLists.map(function(mapNumber){
            // その地図における、配布期間外の町目のセットを先に定義
            const outOfPeriodAddresses=mainSets.filter(eachSet=>(eachSet.start_date > selectedDate || eachSet.end_date < selectedDate) && eachSet.map_number==mapNumber).map(eachSet=>eachSet.address_name);
            return(
            // その地図に配布期間外の町目があるかで分岐
            outOfPeriodAddresses.length>0 ?
            <div key={mapNumber} className={`h-auto base_backColor base_frame mx-auto my-0 border border-black ${minWidth} ${maxWidth} text-center`}>
                <div className={`flex items-center h-9`}>
                    {/* スタッフの選択 */}
                    <SelectParts name="mapStaffs" value={ mapMeta?.[selectedMainProject]?.[mapNumber]?.staffId || "" } onChange={(e)=>handleAssignChangeInMaps(e,mapNumber,outOfPeriodAddresses)} prefix={mapNumber + "："} prefixPercent="w-[20%]" selectPercent="w-[75%]" prefixMinWidth="min-w-10" selectMinWidth="min-w-40" keyValueSets={staffs} allowEmptyOption={false} />
                </div>
                {/* 配布期間外の町目を取得 */}
                <div className={`text-sm w-[30%] mx-auto text-left min-w-40 mb-1`}><p className={`my-0 whitespace-pre-wrap`}>＊下記は配布期間外です<br/>{outOfPeriodAddresses.join("\n")}</p></div>
            </div>
            :

            // 全て期間内のとき
            <div className={`flex items-center base_frame ${minWidth} ${maxWidth} mx-auto my-0 border border-black base_backColor text-center h-9`} key={mapNumber}>
                {/* スタッフの選択 */}
                <SelectParts name="mapStaffs" value={ mapMeta?.[selectedMainProject]?.[mapNumber]?.staffId || "" } onChange={(e)=>handleAssignChangeInMaps(e,mapNumber,[])} prefix={mapNumber + "："} prefixPercent="w-[20%]" selectPercent="w-[75%]" prefixMinWidth="min-w-10" selectMinWidth="min-w-40" keyValueSets={staffs} allowEmptyOption={false} />
            </div>
            );
        })}
        </div>
    )
}
