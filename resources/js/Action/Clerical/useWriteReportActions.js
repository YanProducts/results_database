import React from "react";
import { route } from "ziggy-js"
import putDefaultValueInReportData from "./WriteReport/putDefaultValueInReportData";

export default function useWriteReportActions({isConfirm,setIsConfirm,data,setData,post,dataInReport,setDataInReport,changedId,setChangedId,reportDataInTheProject}){

    // 確認ボタンが押されたら、dataに格納
    React.useEffect(()=>{
        if(!isConfirm){
            setData({...data,"reportedData":[]});
            return;
        }

        // 初期値との差をここで計算(同期的)
        // 変化したIdの配列から、「現在データ-元のデータ」で変化値を求める
        const changedValueSets=changedId.map(changedPlanId=>({
            "planId":changedPlanId,
            "countDifference":reportDataInTheProject.find(dataFromServer=>dataFromServer.plan_id==changedPlanId)?.total_count-dataInReport.find(dataNowInput=>dataNowInput.planId==changedPlanId)?.totalCount || 0
        })
        );

        // dataInReportのうち、changedValueにあるもののみを変換
        setData({...data,
            "reportedData":changedValueSets
        })

    },[isConfirm])

    // 合計の入力が変化した時
    const onReportCountChange=(e,planId)=>{
        const reportTarget=e.target;
        // dataInReportの変化(表示&投稿の元の計算用)
        setDataInReport(
          dataInReport.map(function(eachReport){
            if(eachReport.planId==planId){
                return {
                    ...eachReport,
                    totalCount:reportTarget.value
                }
            }
            return eachReport;
        })

        );

        // 変化した項目に印
        if(!changedId.includes(planId)){
            setChangedId(prev=>[...prev,planId]);
        }

    }

    // 最初からやり直すボタン
    const onStartOverClick=()=>{
        alert("最初からやり直しますか？")
        setDataInReport(        putDefaultValueInReportData({reportDataInTheProject}));
    }

    // 確認ボタンを押したとき
    const onConfirmButtonClick=()=>{
        setIsConfirm(true);
    }

    // 確認キャンセルの時
    const onConfirmCancelClick=()=>{
        // 提出データの一時削除はuseEffect内部で行う
        setIsConfirm(false);
    }


    // 確認OKで提出するとき
    const onConfirmOKClick=()=>{
        post(route("clerical.write_report_post"))
    }

    return {onReportCountChange,onConfirmButtonClick,onConfirmCancelClick,onConfirmOKClick}
}
