import judgeTownCount from "../../../Support/ProjectOperator/Filter/judges/judgeTownCount";

// メインもしくはサブ案件のみが記入されているとき
export default function confirmMainOrSubOnlyInput({assignDataToStaff,selectedDate,inputValues,isEdit=false}){

    // 片側だけ記入されているセット
    const missingSets=[];
    // 入力された値のすべての検証
    Object.entries(inputValues).forEach(function([mainProjectName,inputByMainProjectName]){
        const assignedDataInTheMainProject=assignDataToStaff[selectedDate][mainProjectName].each_data

        const projectNameSets=assignDataToStaff[selectedDate][mainProjectName].project_set


        // 編集の場合は初期でセットされているのでnullを除去
        // 初期投稿の場合の併配同数セットでも同じことが生じないか確認必要
        const filteredInputByMainProjectName=isEdit ?
            Object.fromEntries(
                Object.entries(inputByMainProjectName).map(([townId,eachInputDataByTown],index)=>([[townId],Object.fromEntries((Object.entries(eachInputDataByTown).filter(([projectId,eachCoount])=>eachCoount!=null)))])
           ).filter(([eachTownId,eachData])=>(typeof(eachData)=="object" && Object.keys(eachData).length>0))
        ):inputByMainProjectName

        //案件ごとの入力された値
        Object.entries(filteredInputByMainProjectName).forEach(function([townId,eachInputDataByTown]){

            // 割り振られたデータの案件ごと&内部で地図ごとの情報セットから、その町目のIdと同じものを取得
            let matchedAssignedTown=""

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


            //併配案件同数で設定した時、nullがそのままセットされている可能性
            // ここが同数セットでnullが含まれる

            const matchedSubSets=isEdit ? Object.keys(matchedAssignedTown.sub_sets) : matchedAssignedTown.sub_sets;


            //forEachで回しているeachInputDataByTownは「入力された値」からとってきているから、「行かない町」まで取ることはない
            // そのため、matchedAssignedTownは、必ずこのスタッフの対象の町になる

            // 担当なのに記入されていない併配案件を探す
            // その町で配布すべき併配セット(配列)の中で、まだInputValueにないものを取得(途中で消した場合も含む)
                    matchedSubSets.forEach(function(eachSubId){

                    // 型変換で0が""で引っかかるのに注意
                    if(!(Object.keys(eachInputDataByTown).map(townId=>String(townId))).includes(String(eachSubId)) || eachInputDataByTown?.[eachSubId]==="" || eachInputDataByTown?.[eachSubId]==null){
                        missingSets.push({
                            "mainProjectName":mainProjectName,
                            "missingTown":matchedAssignedTown.address_name,
                            "missingProjectName":projectNameSets["id"+eachSubId],
                        })
                    }
                })


            // (上記のようにinputValueに値がある＝必ず配布対象の町であることが前提)メインが入力されていないとき(途中で消した場合も含む)
            // 型変換で0が""で引っかかるのに注意
            if( !Object.hasOwn(eachInputDataByTown,"main") || eachInputDataByTown.main===""){
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


    // 抜けの可能性ある町目の文面
    const missingSentence=(missingSets.map(eachMissing=>`メイン案件名...${ eachMissing.mainProjectName} 、町目... ${eachMissing.missingTown}、未記入案件...${eachMissing.missingProjectName}`)).join("\n");


    // 狙いがあるかの確認アラート
    const confirm=window.confirm("以下の町目が、メインのみ、もしくは併配のみ記入された状態ですが、このまま提出しますか？\n\n" + missingSentence)

    return confirm;
}
