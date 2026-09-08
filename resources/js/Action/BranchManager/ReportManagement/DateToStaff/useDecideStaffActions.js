import React from "react";
import { route } from "ziggy-js";

// 日付から選択する場合のスタッフ決定の動き
// スタッフから選択の最後と重なる面は多いが変数取得やルーティング、また今後に加わる可能性も考慮して分ける
export default function useDecideStaffActions({date,data,setData,post}){

    React.useEffect(()=>{
        if(!data || Object.keys(data).length==0){
            return;
        }
        post(route("branch_manager.decide_all_for_report_choice_post"));
    },[data])


    // 日付の決定
    const onDecideReport=(staffId)=>{
        setData({
            date:date,
            staffId:staffId
        })
    }

    return {onDecideReport}
}
