// 町目におけるスタッフの変化が変わったとき
export default function useHandleAssignChangeInTowns(e,planId,selectedMainProject,setAssignPlan,mapMeta,setMapMeta,projectsAndTowns){

        // planごとにスタッフを配列にして入れていく

        // スタッフが選択されたvalueになる
        const staffId=e.currentTarget.value;

        //  引数で渡されたplanIdを元にplanId=>スタッフの形で挿入
        // 町目を分割する場合は別途行う
        setAssignPlan(prev=>({
            ...prev,
            [selectedMainProject]:{
                ...prev[selectedMainProject],
                [planId]:staffId
               }
            })
        );


        // このプロジェクトの地図選択をしていないときは以降の処理はカット
        const mapMetaInSelectedMainProject=mapMeta[selectedMainProject];
        if(!mapMetaInSelectedMainProject){
            return;
        }

        // 選択された町目セットの情報
        const selectedTownInformation=projectsAndTowns[selectedMainProject].each_sets.filter(eachSet=>eachSet.planId==planId);

        // planIdに対応するMapNumber
        const mapNumberCorrespondingToPlanId=selectedTownInformation.mapNumber;

        // 上記のMapNumberが既に選択されていた場合、=modifiedに[-その町目](つまり、に該当する場合)
        if(Object.keys(mapMetaInSelectedMainProject).includes(mapNumberCorrespondingToPlanId)){
            setMapMeta(prev=>({
                ...prev,
                [selectedMainProject]:{
                    ...mapMetaInSelectedMainProject,
                    [mapNumberCorrespondingToPlanId]:{
                        ...mapMetaInSelectedMainProject[mapNumberCorrespondingToPlanId],
                        "removeTown":[...mapMetaInSelectedMainProject[mapNumberCorrespondingToPlanId].removeTown,selectedTownInformation.address_name]
                    }
                }
            }))
        }



        // このメイン案件で地図選択から振り終えた人の配列
        const staffIdListsInTheMainProject=Object.values(mapMetaInSelectedMainProject).map(assignedMetaMap=>assignedMetaMap.staffId)

        // 上記の人の中に今回選択した人が含まれていた場合、この町をその人の部分に付け加え=modifiedに[+その町目]を追加(つまり、新たに選択されたstaffIdが上記配列の中に含まれていた時)
        if(staffIdListsInTheMainProject.includes(staffId)){
            setMapMeta(prev=>({
                ...prev,
                [selectedMainProject]:{
                    ...mapMetaInSelectedMainProject,
                     [mapNumberCorrespondingToPlanId]:{
                        ...mapMetaInSelectedMainProject [mapNumberCorrespondingToPlanId],
                        "addTown":[...mapMetaInSelectedMainProject [mapNumberCorrespondingToPlanId].addTown,selectedTownInformation.address_name]
                     }
                }
            }))
        }










}
