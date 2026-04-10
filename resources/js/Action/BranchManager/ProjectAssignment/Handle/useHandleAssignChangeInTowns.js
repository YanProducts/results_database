// 町目におけるスタッフの変化が変わったとき
export default function useHandleAssignChangeInTowns(e,planId,projectsAndTowns,selectedMainProject,setAssignPlan,assignPlan){
        // planごとにスタッフを配列にして入れていく

        // スタッフが選択されたvalueになる
        const staffId=e.currentTarget.value;
        // 選択中のメインプロジェクトの情報が入ったセット
        const selectedProjectSets=projectsAndTowns[selectedMainProject];

        //  引数で渡されたplanIdを元にplanId=>[スタッフリストの形で挿入]
        setAssignPlan(prev=>({
            ...prev,
            planId:[
                ...assignPlan[planId],staffId
            ]
           })
        );
}
