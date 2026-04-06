// スタッフの割り当てを町目から選ぶ場合
export default function TownLists(projectAndTowns,selectedMainProject,staffs,handleAssignChangeInTowns,prefixPercent="w-[30%]",maxWidth="max-w-80", minWidth="min-w-72.5"){

    // 案件情報の取得
    const projectTownLists=projectAndTowns[selectedMainProject];

    // subは上記のsubに[project_nameとid_sets]の入れ子配列で入っている
    const subSets=projectAndTowns["sub"];

    return(
    <>
        <div className="bg-amber-300 w-[80%] min-w-150 max-w-600 mx-auto border-black border-t-2 border-x-2 border-collapse"><h3 className="mb-0 pb-0 text-center font-bold text-lg">選択してください</h3></div>
        {/* townDataには町目データと[sub]配列が入っているが入っている */}
        {/* tableにして、最後にselectBoxを持ってくる */}
        <table className="w-[80%] min-w-150 max-w-600 mx-auto base_backColor border-black border-2 border-collapse">
            <thead className="font-bold  text-center">
             <tr className="border-black border-2">
                <th>町目名</th>
                {/* 併配案件名 */}
                {subSets.map(sub=><th key={sub.project_name}>{sub.project_name}</th>)}
            </tr>
        </thead>
         <tbody className="text-center">
          {ptojectTownLists.map(townData=>
            <tr key={townData.id}>
                {/* 町目名 */}
                <td>{townData.address_name}</td>
                {/* 併配がaddress_nameに含まれてていれば○ */}
                {
                    subSets.map((sub,innerIndex)=><td key={sub.project_name+innerIndex}>
                        {/* sub.id_setsの配列に、mainのidの町目が含まれるか */}
                        { ptojectTownLists["id"].includes(sub.id_sets) ? "○" : "X"}
                    </td>)
                }
               {/* スタッフ選択 */}
                <td>
                <SelectParts name="mapStaffs" value={selectedMapNumber[selectedMainProject][mapNumber]} onChange={(e,mapNumber)=>handleAssignChangeInMaps(e,mapNumber)} prefix={mapNumber} keyValueSets={staffs} maxWidth="max-w-40" minWidth="min-w-30" allowEmptyOption={false} />
                </td>
            </tr>
            )}
         </tbody>
       </table>
    </>
    )
}
