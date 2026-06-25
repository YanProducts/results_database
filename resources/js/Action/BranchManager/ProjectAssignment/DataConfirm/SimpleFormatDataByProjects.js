// 地図選択版
// 確認用に案件ごとに並び替える
export default function SimpleFormatDataByProjects({planIdsAndMapsByMainProjects,staffs,selectedDate,choicedMap}){

    let choicedByProject={}

    // 確認用にプロジェクトごとデータを分ける
    Object.entries(planIdsAndMapsByMainProjects).forEach(([projectName,eachSetByProjectName])=>
        Object.entries(eachSetByProjectName).forEach(function([roundNumber,eachSetByRoundNumber]){
            // そのプロジェクト&roundNumberの案件が持っている情報
            Object.values(eachSetByRoundNumber.map_plan_data).forEach(function(eachMapData){
            // そのプロジェクト&roundNumberの案件が持っている地図を持ってきて、mapNumberを持っているスタッフをchoicedMapから撮ってくる
                const mapNumberInEachMapData=eachMapData.map_number;

                // そのmapNumberを持っているスタッフをchoicedMapより持ってくる
                const staffsHavingTheMapNumber=Object.values(staffs[selectedDate]).filter(function(staffInDate){

                    return(
                        choicedMap[selectedDate]?.[staffInDate]?.[projectName]?.[roundNumber]?.map(choicedMapNumber=>parseInt(choicedMapNumber)).includes(parseInt(mapNumberInEachMapData)) ?? false
                    )
                })

                choicedByProject={
                    ...choicedByProject,
                        [projectName]:{
                            ...choicedByProject?.[projectName],
                            [roundNumber]:{
                                ...choicedByProject?.[projectName]?.[roundNumber],
                                [mapNumberInEachMapData]:staffsHavingTheMapNumber
                            }
                        }
                }

            })
        })
    )
    return choicedByProject;
}
