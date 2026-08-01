import React from "react";

// startDateとendDateの自動調整
export default function adjustDateRange({start,end,setStart,setEnd}){

        // 開始<終了にセット(開始変化)
        React.useEffect(()=>{
            if(end<start){
                // 開始に合わせる
                setEnd(start)
            }
        },[start])

        // 開始<終了にセット(終了変化)
        React.useEffect(()=>{
            if(end<start){
                // 終了に合わせる
                setStart(end)
            }
        },[end])
}
