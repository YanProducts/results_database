// 町目におけるスタッフの変化が変わったとき
export default function useHandleAssignChangeInTowns(e,planId,selectedMainProject,setAssignPlan){

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
}
