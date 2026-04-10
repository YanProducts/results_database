// mapにおけるスタッフの変化が変わったとき
export default function useHandleAssignChangeInMaps(e,mapNumber,projectsAndTowns,selectedMainProject,setSelectedMapNumber,setAssignPlan,assignPlan){
    // スタッフが選択されたvalueになる
    const staffId=e.currentTarget.value;
    // 選択中のメインプロジェクトの情報が入ったセット
    const selectedProjectSets=projectsAndTowns[selectedMainProject];

    // optionで選択中の地図番号を変更(表示用。handle関数には引数で渡す)
    setSelectedMapNumber(prev=>({
        prev,
        [selectedMainProject]:{
            ...selectedMapNumber[selectedMainProject],
            [mapNumber]:staffId
        }
     })
    );

    // 選択中のメインプロジェクトのセットから、地図番号が適合するものをフィルター
    const mapNumberSets=selectedProjectSets.filter((eachSet)=>eachSet.map_number==mapNumber);

    // 地図番号でフィルターされたセットから、[planId,staffId(全て同じ)]の入れ子の配列で取得。それをその後、Entriesでplanidをキーにしたオブジェクトに変更
    const planIdArray=mapNumberSets.map((eachFilteredSet)=>[eachFilteredSet.plan_id,[staffId]]);
    setAssignPlan(
        ...assignPlan,
        // 新たにできたキーとスタッフのセット
        Object.fromEntries(planIdArray)
    )


}
