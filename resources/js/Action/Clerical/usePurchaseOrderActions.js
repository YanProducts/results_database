import { route } from "ziggy-js";
import axios from "axios";
import React from "react";

export default function usePurchaseOrderActions({selectedStaff,setSelectedStaff,selectedStartMonth,setSelectedStartMonth,selectedEndMonth,setSelectedEndMonth,limitMonth,setLimitMonth,processingRef,setButtonOk,monthSets}){

    // １初回のmonthSetsがセットされた後、２Dateをselectしておらず、かつmonthSetsを先に変化させたとき
    // 上記の場合に、selectedDate系列の初期値を設定
    React.useEffect(()=>{
        if(!monthSets || Object.keys(monthSets)==0 || selectedStartMonth || selectedEndMonth){
            return
        }
        console.log("a")
        setSelectedStartMonth(new Date(Object.keys(monthSets)[0]))
        setSelectedEndMonth(new Date(Object.keys(monthSets)[0]))
    },[monthSets])


    // 開始月<終了月にセット(開始月変化)
    React.useEffect(()=>{
        console.log(selectedEndMonth)
        console.log(selectedStartMonth)
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

    // 取得制限月の変更
    const onLimitMonthChange=(e)=>{
        const target=e.currentTarget;
        // その年の数*12が取得する月
        setLimitMonth(target.value*12)
    }

    // 決定ボタン
    const onDecidePurchase=async(e)=>{
        e.preventDefault();
        // ロジック内部での二重投稿の制御
        if (processingRef.current) return;
          processingRef.current = true;

        // レスポンス終了までボタンを押せないようにする(Inertiaではないのでprocessingは反映されない) (またRefだけだとUIを動的に変更しない)
        setButtonOk(false)

        // 非同期で送る
        try{
            // axiosで非同期投稿
            const response=await axios.post(route("clerical.export_purchase_order_post"),{
                "staffId":selectedStaff,
                "startMonth":selectedStartMonth,
                "endMonth":selectedEndMonth
            })
            // 返却されていないとき
            if(!response.data.downloadOk){
                throw new Error("サーバー処理時のエラーです")
            }
            alert("ダウンロードが完了しました。\nファイルを確認してください")
        }catch(e){
            console.log(e.message)
            alert("何らかのエラーが発生し、エクスポートができませんでした")
        }finally{
            // ロジックを可能にする
            processingRef.current=false;
            // UIを動かせるようにする
            setButtonOk(true)
        }
    }

    return {onStaffChange,onSelectedStartMonthChange,onSelectedEndMonthChange,onLimitMonthChange,onDecidePurchase}
}
