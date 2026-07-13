// メインもしくはサブ案件のみが記入されているとき
export default function confirmMainOrSubOnlyInput({assignDataToStaff,selectedDate,inputValues}){

    // 片側だけ記入されているセット
    const missingSets=[];

    // 入力された値のすべての検証
    Object.entries(inputValues).forEach(function([mainProjectName,inputByMainProjectName]){
        const assignedDataInTheMainProject=assignDataToStaff[selectedDate][mainProjectName].each_data

        const projectNameSets=assignDataToStaff[selectedDate][mainProjectName].project_set

        //案件ごとの入力された値
        Object.entries(inputByMainProjectName).forEach(function([townId,eachInputDataByTown]){

            // 割り振られたデータの案件ごと&内部で地図ごとの情報セットから、その町目のIdと同じものを取得
            let matchedAssignedTown=""


                        console.log(townId) //その街でinputされたId
            console.log(eachInputDataByTown) //その街でinputされたデータ
            console.log(assignedDataInTheMainProject)



           for (const assignedDataByMap of Object.values(assignedDataInTheMainProject)){
                    // 対象の町がmapに存在すれ、matchされた街のデータが返る
                    matchedAssignedTown=Object.values(assignedDataByMap).find(function(assignedDataByTown){
                    return townId==assignedDataByTown.assign_id}
                    );
                    // そのマップにある場合は、その後のforを飛ばす
                    if(matchedAssignedTown){
                        break;
                    }
            }


            //forEachで回しているeachInputDataByTownは「入力された値」からとってきているから、「行かない町」まで取ることはない
            // そのため、matchedAssignedTownは、必ずこのスタッフの対象の町になる

            // 担当なのに記入されていない併配案件を探す
            // その町で配布すべき併配セット(配列)の中で、まだInputValueにないものを取得(途中で消した場合も含む)
                matchedAssignedTown.sub_sets.forEach(function(eachSubId){

                    if(!(Object.keys(eachInputDataByTown).map(townId=>String(townId))).includes(String(eachSubId)) || eachInputDataByTown?.[eachSubId]=="" ){
                        missingSets.push({
                            "mainProjectName":mainProjectName,
                            "missingTown":matchedAssignedTown.address_name,
                            "missingProjectName":projectNameSets["id"+eachSubId],
                        })
                    }
                })

            // (上記のようにinputValueに値がある＝必ず配布対象の町であることが前提)メインが入力されていないとき(途中で消した場合も含む)
            if(!eachInputDataByTown?.main || eachInputDataByTown.main==""){
                // 併配を入力していてメインが入力されていない時(そもそもメインが記入されていない)
                    missingSets.push({
                            "mainProjectName":mainProjectName,
                            "missingTown":matchedAssignedTown.address_name,
                            "missingProjectName":mainProjectName,
                        })
            }
        })

    })
    // 正常に獲得されている場合はtrueを返す
    if(missingSets.length==0){
        return true;
    }

    console.log(missingSets)
    // 抜けの可能性ある町目の文面
    const missingSentence=(missingSets.map(eachMissing=>`メイン案件名...${ eachMissing.mainProjectName} 、町目... ${eachMissing.missingTown}、未記入案件...${eachMissing.missingProjectName}`)).join("\n");


    // 狙いがあるかの確認アラート
    const confirm=window.confirm("以下の町目が、メインのみ、もしくは併配のみ記入された状態ですが、このまま提出しますか？\n\n" + missingSentence)

    return confirm;
}
