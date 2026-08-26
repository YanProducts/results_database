// 報告書編集用の初期値の設定(この値を定義時に代入)
export default function getDefaultValueForEditReport({assignWithRecords,date}){
    let defualtInputValue={};

    defualtInputValue[date]=Object.entries(assignWithRecords[date]).map(([mainProjectName,dataByMainProject],index)=>({
        // メイン案件ごとキーをセット
        [mainProjectName]:
            // 案件名以外のデータが配列で入っている
            Object.values(dataByMainProject.each_data).flatMap(dataByMapNumber=>
                Object.values(dataByMapNumber).map(dataInEachTown=>({
                    // 内側のキーはassignIdごとにセット
                    [dataInEachTown.assign_id]:{
                        // メイン案件の初期の数値
                        main:dataInEachTown.counts ?? null,
                        // 併配案件の初期の数値
                        ...dataInEachTown.sub_sets
                    }})
        ))
    }));

    return defualtInputValue;
}
