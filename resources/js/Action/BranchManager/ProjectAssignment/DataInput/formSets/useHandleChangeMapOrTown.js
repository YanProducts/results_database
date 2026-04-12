 // 表示するのはmapからかtownからか
export const useHandleChangeMapOrTown=(e,needNumber,setNeedNumber,selectedMainProject,setAssignPlan,setSelectedMapNumber)=>{
        // townListからmapNumberはリセット(mapの1部が違うなどの問題が生じるため)
        if(needNumber=="townList" && e.currentTarget.value=="mapNumber"){
            if(!confirm("この案件の分割データは初期化されます\nよろしいですか？")){
                return;
            }

            setSelectedMapNumber({[selectedMainProject]:{}});
            setAssignPlan({[selectedMainProject]:{}});

        }
        setNeedNumber(e.currentTarget.value);
    }
