import SelectParts from "../../../../Common/SelectParts";
// スタッフの割り当てを町目から選ぶ場合
export default function TownLists({projectsAndTowns,selectedMainProject,assignPlan,staffs,handleAssignChangeInTowns,prefixPercent="w-[30%]",maxWidth="max-w-80", minWidth="min-w-72.5"}){

    // メイン案件が選択されていないとき
    if(!selectedMainProject){
        return<div className={`text-center base_frame base_backColor ${minWidth} ${maxWidth} mb-3`}><p>メイン案件を選択してください</p></div>
    }

    // 案件情報の取得
    const projectTownLists=projectsAndTowns[selectedMainProject];

    // subは上記のsubに[project_nameとid_sets]の入れ子配列で入っている
    const subSets=projectTownLists["sub_sets"];
    // mainのセットはこの中に捕捉
    const mainSets=projectTownLists["each_sets"];

    return(
    <>
        <div className="bg-amber-300 w-[80%] min-w-150 max-w-600 mx-auto border-black border-2 mb-3"><h3 className="mb-0 pb-0 text-center font-bold text-lg">選択してください</h3></div>
        {/* townDataには町目データと[sub]配列が入っているが入っている */}
        {/* tableにして、最後にselectBoxを持ってくる */}
        <table className="w-[90%] min-w-150 max-w-600 mx-auto base_backColor border-black border-2 border-collapse mb-4">
            <thead className="font-bold  text-center">
             <tr className="border-black border-2">
                <th className="border-black border-2 border-collapse">町目名</th>
                {/* 併配案件名(案件名はsubsetsそれぞれのキー) */}
                {Object.keys(subSets).map(sub=><th key={sub} className="border-black border-2 border-collapse">{sub}</th>)}
                <th className="border-black border-2 border-collapse">スタッフ名</th>
            </tr>
        </thead>
         <tbody className="text-center">

          {mainSets.map(function(townData){
            const planId=townData.id;
            return(
             <tr className="border-black border-2 border-collapse" key={planId}>
                {/* 町目名 */}
                <td className="border-black border-2 border-collapse">{townData.address_name}</td>
                {/* 併配がaddress_nameに含まれてていれば○ */}
                {
                    Object.entries(subSets).map((eachSubSet,index)=><td key={index} className="border-black border-2 border-collapse">
                        {/* 併配のidリストの配列に、mainのidの町目が含まれるか */}
                        { eachSubSet[1].includes(planId) ? "○" : "X"}
                    </td>)
                }

               {/* スタッフ選択 */}
                <td className="border-black border-2 border-collapse">
                <SelectParts name="townStaffs"  value={assignPlan?.[selectedMainProject]?.[planId] ?? ""} onChange={(e)=>handleAssignChangeInTowns(e,planId)} prefix={""} keyValueSets={staffs} prefixPercent={"w-[0%]"} prefixMinWidth={"min-w-0"} selectPercent={"w-[100%]"} selectMinWidth={"min-w-12"} maxWidth="max-w-20" minWidth="min-w-12" allowEmptyOption={false} />
                </td>
            </tr>
            )}
            )}
         </tbody>
       </table>
    </>
    )
}
