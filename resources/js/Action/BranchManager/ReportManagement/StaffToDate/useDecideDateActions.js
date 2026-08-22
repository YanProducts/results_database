import React from "react"
import { route } from "ziggy-js";

// スタッフ→日付の決定
export default function useDecideDateActions({data,setData,post}){

    // dataがセットされたらpost
    React.useEffect(()=>{
        if(!data || Object.keys(data).length==0){
            return;
        }
        post(route("branch_manager.decide_date_for_report_choice_post"));
    },[data])

    // どの報告書を確認するかの決定
    const onDecideReport=(e,date,staffId)=>{
        setData({
            "date":date,
            "staffId":staffId
        })

    }
    return { onDecideReport }
}
