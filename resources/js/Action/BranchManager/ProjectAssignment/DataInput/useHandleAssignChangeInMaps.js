// mapにおけるスタッフの変化が変わったとき
export default function useHandleAssignChangeInMaps(e,mapNumber,outOfPeriodAddresses,projectsAndTowns,selectedDate,mapMeta,selectedMainProject,setMapMeta,setAssignPlan){

    // スタッフが選択されたvalueになる
    const staffId=e.currentTarget.value;
    // 選択中のメインプロジェクトの情報が入ったセット
    const selectedProjectSets=projectsAndTowns[selectedMainProject];

    // optionで選択中の地図番号を変更(表示用。handle関数には引数で渡す)
    setMapMeta(prev=>({
        ...prev,
        [selectedMainProject]:{
            ...mapMeta[selectedMainProject],
            [mapNumber]:{
                "staffId":staffId,
                "addTown":[],
                "removeTown":[],
                // 期間外セット
                "outOfPeriodAddresses":outOfPeriodAddresses
            }
        }
     }))


    // 選択中のメインプロジェクトのセットから、地図番号が適合し、かつ期間内に入っているものをフィルター
    const mapNumberSets=(selectedProjectSets.each_sets).filter(eachSet=>eachSet.map_number==mapNumber && (eachSet.start_date <= selectedDate && eachSet.end_date >= selectedDate));

    // 地図番号でフィルターされたセットから、[planId,staffId(全て同じ)]の入れ子の配列で取得。それをその後、Entriesでplanidをキーにしたオブジェクトに変更
        setAssignPlan(prev=>({
            ...prev,
            // 今のメイン案件に、該当する地図の町目Idをキーに、スタッフを値に設定
            [selectedMainProject]:{
                ...(prev[selectedMainProject] ?? {}),

                // 配列をオブジェクト化
                ...Object.fromEntries(mapNumberSets.map((eachFilteredSet)=>(
                    [eachFilteredSet.id,staffId]
                )))

            }
        }));

}
