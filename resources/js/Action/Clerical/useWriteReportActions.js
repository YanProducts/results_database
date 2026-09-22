import React from "react";
import { route } from "ziggy-js"
import putDefaultValueInReportData from "./WriteReport/putDefaultValueInReportData";
import disappearValidation from "../../Support/Common/disappearValidation";

export default function useWriteReportActions({firstAttention,setFirstAttention,validationHidden,setValidationHidden,errors,isConfirm,setIsConfirm,data,setData,post,dataInReport,setDataInReport,changedId,setChangedId,reportDataInTheProject}){

    // 初期の注意書きを消す
    React.useEffect(()=>{
        if(!firstAttention){
            return;
        }
        const timer=setTimeout(()=>{setFirstAttention(false)},3000)
        return ()=>{clearTimeout(timer)};
    },[])

    // エラーの削除(内部でuseEffect使用)
    disappearValidation({errors,setValidationHidden})

    // 確認ボタンが押されたら、dataに格納
    React.useEffect(()=>{
        if(!isConfirm){
            setData(prev=>({...prev,"changedDifference":[]})); //送信するデータは空にする
            return;
        }

        // 初期値との差をここで計算(同期的)
        // 変化したIdの配列から、「現在データ-元のデータ」で変化値を求める
        const changedValueSets=changedId.map(changedPlanId=>({
            "planId":changedPlanId,
            "countDifference": ((dataInReport.find(dataNowInput=>dataNowInput.planId==changedPlanId)?.totalCount || 0)) -(reportDataInTheProject[changedPlanId].total_counts || 0)
        })
        );

        // dataInReportのうち、changedValueにあるもののみを変換
        setData(prev=>({...prev,
            "changedDifference":changedValueSets
        }))

    },[isConfirm])

    // 合計の入力が変化した時
    const onReportCountChange=(e,planId)=>{
        const reportTargetValue=e.target.value;

       if (reportTargetValue && !/^\d+$/.test(reportTargetValue)) {
            alert("数値以外は入力できません");
            return;
       }

        // dataInReportの変化(表示&投稿の元の計算用)
        setDataInReport(
          dataInReport.map(function(eachReport){
            if(eachReport.planId==planId){
                return {
                    ...eachReport,
                    totalCount:reportTargetValue
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

        if(!confirm("最初からやり直しますか？")){
            return;
        }
        setChangedId([]);
        setDataInReport(putDefaultValueInReportData({reportDataInTheProject}));
    }

    // 確認ボタンを押したとき
    const onConfirmButtonClick=()=>{
        setIsConfirm(true);
    }

    // 確認キャンセルの時
    const onConfirmCancelClick=()=>{
        // 現代データはstateで置かれているため。提出データは空にする
        setData((prev=>({...prev,
            "changedDifference":[]
        })));
        // 提出データの一時削除はuseEffect内部で行う
        setIsConfirm(false);
    }


    // 確認OKで提出するとき
    const onConfirmOKClick=()=>{
        post(route("clerical.write_report_post"))
        setIsConfirm(false)
    }

    return {onReportCountChange,onStartOverClick,onConfirmButtonClick,onConfirmCancelClick,onConfirmOKClick}
}
