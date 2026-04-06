import SelectParts from "../../../Common/SelectParts";

// スタッフの割り当てをMapNumberから選ぶ場合
export default function MapLists(projectAndTowns,selectedMainProject,staffs,handleAssignChangeInMaps,prefixPercent="w-[30%]",maxWidth="max-w-80", minWidth="min-w-72.5"){

    // map番号を重複なしで取得
    const mapDuplicatedNumberLists=projectAndTowns[selectedMainProject].map(each_set=>each_set.map_number);
    const mapNumberLists=[...new Sets(...mapDuplicatedNumberLists)]

    return(
        mapNumberLists.map(mapNumber=>
            <div lassName={`flex items-center base_frame ${minWidth} ${maxWidth} mx-auto my-3`} key={mapNumber}>
                <span className={`inline-block ${prefixPercent} min-w-32 text-right`}>{mapNumber}</span>
                {/* selectOptinonSetsにスタッフをユーザー名⇨本名の形式で渡す */}
                {/* <select className="inline-block w-[60%] min-w-35 bg-white border-black border rounded-b-sm" value={selectedMapNumber[selectedMainProject][mapNumber]} onChange={(e,mapNumber)=>handleAssignChangeInMaps(e,mapNumber)}>
                  <>
                    <option hidden value="">選択してください</option>
                    {staffs.map(staff=>
                    <option value={staff.id}>{staff?.staff_name || staff.user_name}</option>
                    )}
                  </>
                </select> */}
                {/* スタッフの選択 */}
                <SelectParts name="mapStaffs" value={selectedMapNumber[selectedMainProject][mapNumber]} onChange={(e,mapNumber)=>handleAssignChangeInMaps(e,mapNumber)} prefix={mapNumber} keyValueSets={staffs} allowEmptyOption={false} />
            </div>
        )
    )
}
