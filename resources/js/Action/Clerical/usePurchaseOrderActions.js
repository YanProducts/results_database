import React from "react";
import createAndDownloadCSV from "./PurchaseOrder/createAndDownloadCSV";

export default function usePurchaseOrderActions({errors,clearErrors,selectedStaff,setSelectedStaff,selectedStartMonth,setSelectedStartMonth,selectedEndMonth,setSelectedEndMonth,limitMonth,setLimitMonth,processingRef,setButtonOk,monthSets}){

    // １初回のmonthSetsがセットされた後、２Dateをselectしておらず、かつmonthSetsを先に変化させたとき
    // 上記の場合に、selectedDate系列の初期値を設定
    React.useEffect(()=>{
        if(!monthSets || Object.keys(monthSets)==0 || selectedStartMonth || selectedEndMonth){
            return
        }
        setSelectedStartMonth(new Date(Object.keys(monthSets)[0]))
        setSelectedEndMonth(new Date(Object.keys(monthSets)[0]))
    },[monthSets])


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

    // ダウンロードのエラーがセットされたら、3秒後にはエラーを消す
    React.useEffect(()=>{
       if (Object.keys(errors).length > 0) {
            const clearErrorTimeout=setTimeout(()=>clearErrors(),3000)
            return ()=>{clearTimeout(clearErrorTimeout)};
        }
        return ()=>{}
    },[errors])

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

    // 取得制限月の変更
    const onLimitMonthChange=(e)=>{
        const target=e.currentTarget;
        // その年の数*12が取得する月
        setLimitMonth(target.value*12)
    }

    // 決定ボタン
    const onDecidePurchase=async(e)=>{
        createAndDownloadCSV({e,processingRef,setButtonOk,selectedStaff,selectedStartMonth,selectedEndMonth})
    }

    return {onStaffChange,onSelectedStartMonthChange,onSelectedEndMonthChange,onLimitMonthChange,onDecidePurchase}
}
