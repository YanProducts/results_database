export default function AssignSetsForConfirming({assignPlan,staffs}){
    console.log(assignPlan);

    let assignPlanByStaff={};
    // スタッフを１人１人見ていく
    staffs.map((staff)=>function(){
        // 入力したデータをメインプロジェクトごとに取得
        Object.entries(assignPlan).map((mainSets,index)=>{
            // プロジェクト名(assignPlanのキー)
            const mainProjectName=mainSets[0];
            // planId:スタッフのオブジェクト(assignPlanの値)
            const planIdsInTheMainProject=mainSets[1];

            // そのプロジェクトでそのスタッフが担当予定の案件ごと町目
            const planIdsInTheStaff=Object.values(planIdsInTheMainProject).filter((staffInPlanId)=>staffInPlanId===staff)

            // オブジェクトに格納(そのスタッフのメイン案件に関しては1回の施行でOKなのでmainProjectName側にスプレッド構文は必要ない)
            assignPlanByStaff[staff]={
                ...assignPlanByStaff[staff],
                mainProjectName:[planIdsInTheStaff]
            }



        })
    })



    return(
        <>

        </>
    )

}
