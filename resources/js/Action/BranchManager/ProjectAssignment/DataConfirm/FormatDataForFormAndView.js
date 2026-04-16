// 一時的に保存されたデータをformの形式に変換
export default function FormatDataForFormAndView({assignPlan,staffs,projectsAndTowns,setAssignPlanForConfirmView,setData,mapMeta}){

    // 格納用。setDataでallDataの内部に{["staffId":,"planId":],["staffId","planId"]...この配列で格納}
    let assignPlanForForm=[];

    // 表示用。スタッフ名前:メイン案件:[map:地図番号(もしくは地図番号からの改変)][townSets:町名セット]の文字列のオブジェクトで格納
    let assignPlanForView={};

    // スタッフを１人１人見ていく
    (Object.keys(staffs)).forEach((staffId)=>{
        const staffName=staffs[staffId];
        // そのスタッフにおける、form挿入用のフォーム
        let planIdsForForm=[];

        // 入力したデータをメインプロジェクトごとに取得
        (Object.entries(assignPlan)).forEach((mainSets,index)=>{

            // プロジェクト名(assignPlanのキー)
            const mainProjectName=mainSets[0];

            // planId:スタッフのオブジェクト(assignPlanの値)
            const planIdsInTheMainProject=mainSets[1];

            // そのプロジェクトで、そのスタッフが担当予定の案件ごと町目
            const planIdsInTheStaff=Object.keys(Object.fromEntries(Object.entries(planIdsInTheMainProject).filter((staffInPlanId)=>staffInPlanId[1]===staffId)))

            //そのプロジェクトで、そのスタッフが担当予定の土台地図と改変(該当地図で複数面行く場合も列挙)
            const mapDataInTheProjectAndStaff=Object.entries(mapMeta[mainProjectName]).filter(mapDataInTheProject=>Number(mapDataInTheProject[1].staffId)==Number(staffId));

            //上記を基にした地図のコメント(該当地図で複数面いくときもあり)
            const mapComment=mapDataInTheProjectAndStaff.map(eachMapData=>
                "Map" + eachMapData[0] + ((eachMapData[1]?.addTown && eachMapData[1].addTown.length>0 ) ? "\+" + eachMapData[1].addTown.join(",") : "") + ((eachMapData[1]?.removeTown && eachMapData[1].removeTown.length>0) ? "\-" + eachMapData[1].remove.join(",") : "")
            ).join("\n");

            // 表示用
            // 当該スタッフにおける、そのメイン案件をキーに持つオブジェクトに格納(そのスタッフのメイン案件に関しては1回の施行でOKなのでmainProjectName側にスプレッド構文は必要ない)
            assignPlanForView[staffName]={
                ...assignPlanForView[staffName],
                [mainProjectName]:{
                    // 内部ではplanIdがidとして、住所がaddress_nameとして格納
                    "planId":planIdsInTheStaff.map(planId=>(projectsAndTowns[mainProjectName].each_sets).filter(eachPlanData=>eachPlanData.id==planId)[0].address_name),
                    "mapComment":mapComment
                }
            }

            // 投稿用に追加
            planIdsForForm=[...planIdsForForm,...planIdsInTheStaff];





        });


        // data投稿用土台(planIdで案件も一意に決まるので、projectNameを展開する必要はない)
        assignPlanForForm.push({
            staffId:staffId,
            planIds:planIdsForForm
        });

    })

    // 表示用
    setAssignPlanForConfirmView(assignPlanForView);
    // 投稿用
    setData({"allData":assignPlanForForm});

}
