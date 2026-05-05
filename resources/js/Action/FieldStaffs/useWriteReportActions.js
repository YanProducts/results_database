import React from "react"

export default function useWriteReportActions({inputValues,setInputValues,inputRefs,selectedDate,setSelectedDate,isConfirm,setIsConfirm,setData,post}){


    // テスト用
    React.useEffect(()=>{
        console.log(inputValues)
    },[inputValues])

    // 日付が変更されたらpost用のデータにセット(他のデータは自動的に初期化)
    React.useEffect(()=>{
        setData({
            "date":selectedDate,
            "reportData":[]
        })
    },[selectedDate])

    // 確認画面になったらform用のデータに変換
    React.useEffect(()=>{
        if(isConfirm){

        }
    },[isConfirm])

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
        inputRefs.current[mainProjectName][trIndex][index]=focus;

        // input要素のvalueの更新
        setInputValues(prev=>({
            ...prev,
            [mainProjectName]:{
                ...(inputValues?.[mainProjectName] || {}),
                [assignId]:{
                    ...(inputValues?.[mainProjectName]?.[assignId] || {}),
                    [subProjectId ?? "main"]:target
                }
            }
        }));
    }

    // 決定ボタンを押した際は確認ページを表示する
    const onSubmitBtnClick=(e)=>{
            e.preventDefault();
            setIsConfirm(true);
    }

    // 確認OKの時
    const onConfirmOkClick=()=>{
        // バリデーション対策にinputデータを初期化はしないでおく

        // ポスト
        post("");

        // バリデーション失敗した時に備えてconfirmはチェンジ
        setIsConfirm(false)
    }

    // キャンセルの時
    const onConfirmCancelClick=()=>{
        // データの初期化(inputデータは持っておく)
        setData();
    }

    return {onSelectedDateChange,onAssignedInputChange,onSubmitBtnClick,onConfirmOkClick,onConfirmCancelClick}

}
