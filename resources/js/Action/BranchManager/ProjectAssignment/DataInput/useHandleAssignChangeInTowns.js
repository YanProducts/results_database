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
        const selectedTownInformation=projectsAndTowns[selectedMainProject].each_sets.filter(eachSet=>eachSet.id==planId)[0];
        const address_name=selectedTownInformation.address_name;
        // 既にその人が振られているmapに、新たに振られた町目をaddTownで追加
        // 人が変更された町目を含む地図から、現在担当のスタッフをremoveTownでマイナス

        // １：既に変更された町目を再変更する場合、２：翌日以降に、その地図を振る場合が未設定

        setMapMeta(prev=>({
            ...prev,
            [selectedMainProject]:Object.fromEntries(Object.entries(mapMetaInSelectedMainProject).map(function(eachMetaMap){
                    let newEntriesArray={};
                    const eachMapNumber=eachMetaMap[0];
                    const eachMapInfo=eachMetaMap[1];

                    // 既にその人が振られているmapに、新たに振られた町目をaddTownで追加
                    newEntriesArray=Number(eachMapInfo.staffId)==Number(staffId) ? [eachMapNumber,{...eachMapInfo,"addTown":[...eachMapInfo.addTown,address_name]}] : [eachMapNumber,eachMapInfo]

                    // 人が変更された町目を含む地図から、現在担当のスタッフをremoveTownでマイナス
                    newEntriesArray=Number(eachMapNumber==selectedTownInformation.map_number) ? [eachMapNumber,{...newEntriesArray[1],"removeTown":[...eachMapInfo.removeTown,address_name]}] : [eachMapNumber,newEntriesArray[1]]

                    return newEntriesArray;
                    }
                ))
            }))
         }

