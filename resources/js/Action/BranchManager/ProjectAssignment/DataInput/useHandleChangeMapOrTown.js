 // 表示するのはmapからかtownからか
export const useHandleChangeMapOrTown=(e,needNumber,setNeedNumber,selectedMainProject,setAssignPlan,setMapMeta)=>{
        // townListからmapNumberはリセット(mapの1部が違うなどの問題が生じるため)
        if(needNumber=="townList" && e.currentTarget.value=="mapNumber"){
            // 途中で案件を変えての操作が可能なため、その案件に限らず地図分割は全て初期化する
            if(!confirm("この案件の分割データは初期化されます\nよろしいですか？")){
                return;
            }

            setMapMeta(prev=>({
                ...prev,
                [selectedMainProject]:{}
            }));
            setAssignPlan(prev=>({
                ...prev,
                [selectedMainProject]:{}
            }));
        }
        setNeedNumber(e.currentTarget.value);
    }
