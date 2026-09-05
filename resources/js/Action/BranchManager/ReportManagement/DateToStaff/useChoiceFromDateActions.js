import React from "react";
import { route } from "ziggy-js";

// 日付から選択する場合の動き
export default function useChoiceFromDateActions({data,setData,post}){

    React.useEffect(()=>{
        if(!data || Object.keys(data).length==0){
            return;
        }
        post(route("branch_manager.choice_report_date_target_post"))
    },[data])


    // 日付の決定
    const onDateClick=(e)=>{
        const target=e.currentTarget;
        setData({
            "date":target.value
        })
    }

    return {onDateClick}
}
