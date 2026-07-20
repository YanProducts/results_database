import { route } from "ziggy-js";
import React from "react";

export default function usePurchaseOrderActions({post,data,setData,setSelectedStaff,selectedStartMonth,setSelectedStartMonth,selectedEndMonth,setSelectedEndMonth,limitMonth,setLimitMonth}){

    // 開始月<終了月にセット(開始月変化)
    React.useEffect(()=>{
        if(selectedEndMonth<selectedStartMonth){
            // 開始月に合わせる
            setSelectedEndMonth(selectedStartMonth)
        }
    },[selectedStartMonth])

    // 開始月<終了月にセット(終了月変化)
    React.useEffect(()=>{
        if(selectedEndMonth<selectedStartMonth){
            // 終了月に合わせる
            setSelectedStartMonth(selectedEndMonth)
        }
    },[selectedEndMonth])

    // スタッフの変更
    const onStaffChange=(e)=>{
        const target=e.currentTarget;
        setSelectedStaff(target.value);
    }

    // 開始月の変更
    const onSelectedStartMonthChange=(e)=>{
        const target=e.currentTarget;
        setSelectedStartMonth(new Date(target.value));
    }
    // 終了月の変更
    const onSelectedEndMonthChange=(e)=>{
        const target=e.currentTarget;
        setSelectedEndMonth(new Date(target.value));
    }
    // 所得制限月の変更
    const onLimitMonthChange=(e)=>{
        // さらに１年前を表示
        setLimitMonth(limitMonth-12)
    }

    // 決定ボタン
    const onDecidePurchase=(e)=>{
        e.preventDefault();
    }

    return {onStaffChange,onSelectedStartMonthChange,onSelectedEndMonthChange,onLimitMonthChange,onDecidePurchase}
}
