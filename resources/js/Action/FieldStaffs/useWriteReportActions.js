import React from "react";
import { route } from "ziggy-js";

export default function useWriteReportActions({inputValues,setInputValues,inputRefs,assignDataToStaff,selectedDate,setSelectedDate,isConfirm,setIsConfirm,setData,post}){


    // 日付が変更されたらpost用のデータにセット(他のデータは自動的に初期化)
    React.useEffect(()=>{
        setData({
            "date":selectedDate,
            "reportData":[]
        })
    },[selectedDate])


    // formデータがセットされたらbuttonをアクティブにする

    // 日付の変更
    const onSelectedDateChange=(e)=>{
        // UI
        setSelectedDate(e.currentTarget.value)
    }

    // 入力された部数が変化したとき
    const onAssignedInputChange=({e,assignId,subProjectId=null,mainProjectName,trIndex,index})=>{
        const target=e.currentTarget.value;
        if(target && !Number.isInteger(Number(target))){
            alert("数値以外は入力できません")
            return;
        }

        // 変化したinput要素をfocus
        inputRefs.current[mainProjectName][trIndex][index]?.focus();

        // input要素のvalueの更新
        setInputValues(prev=>({
            ...prev,
            [mainProjectName]:{
                ...(prev?.[mainProjectName] || {}),
                [assignId]:{
                    ...(prev?.[mainProjectName]?.[assignId] || {}),
                    [subProjectId ?? "main"]:target
                }
            }
        }));
    }

    // 決定ボタンを押した際は確認ページを表示する
    const onSubmitBtnClick=(e)=>{
            e.preventDefault();
            // 投稿データは１：メインはassignIdで案件に関わらずいける。２：サブはassignIdに紐づいたplanIdからmainIdを検索可能
            // そのため、[assignId:...,mainCount:...,subCounts:[projectId:...,subCount:...]の入れ子この配列にする
            const dataForForm=[];
            Object.entries(inputValues).forEach((eachInputValue,index)=>{
                Object.entries(eachInputValue[1]).forEach(eachSets=>{
                    const eachMainId=eachSets[0];
                    const eachCount=eachSets[1];
                    dataForForm.push({
                        "assignId":eachMainId,
                        "mainCount":eachCount.main ?? 0,
                        "subData":
                            Object.entries(eachCount).map((IdCountSets)=>
                              IdCountSets[0] !=="main" ? {"projectId":IdCountSets[0],"subCount":IdCountSets[1]} : null
                            ).filter(obj=>obj!=null)
                    })
                })
            })
            setData({
                "date":selectedDate,
                "reportData":dataForForm
            });
            setIsConfirm(true);
    }

    // 確認OKの時
    const onConfirmOkClick=()=>{
        // バリデーション対策にinputデータを初期化はしないでおく

        // ポスト
        post(route("field_staff.write_report_post"));

        // バリデーション失敗した時に備えてconfirmはチェンジ
        setIsConfirm(false)
    }

    // キャンセルの時
    const onConfirmCancelClick=()=>{
        // 投稿データの初期化(inputデータは持っておく)
        setData();
        // UIを戻す
        // setIsConfirm(false);
    }

    return {onSelectedDateChange,onAssignedInputChange,onSubmitBtnClick,onConfirmOkClick,onConfirmCancelClick}

}
