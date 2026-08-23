import React from "react";

// 報告書編集の動き
export default function useEditReportActions({assignWithRecords,inputValues,setInputValues,inputRefs,setChangedData,setIsConfirm,data,setData,post,setIsBigMedia}){


    // 大きなデバイスかどうか
    React.useEffect(()=>{
        // windowは分割点に差し掛かっているかのみ取得
        const bigJudge=window.matchMedia("(min-width:500px)")
        const mediaChange=()=>{
            setIsBigMedia(bigJudge?.matches || false)
        }
        // そのmediaが変化した場合に変化
        bigJudge.addEventListener("change",mediaChange)
        return ()=>{bigJudge.removeEventListener("change",mediaChange)};
    },[]);

    
    // 報告書に初期値の挿入(すでに投稿されているデータ)
    React.useEffect(()=>{


    },[assignWithRecords])//初回のみ


    // 持ち出し&返却のまとめ //mapNumberは必要ない
    const onIssuedOrReturnedCountsChange=(e,mainProjectName,eachProjectName,setState)=>{

        const targetValue=e.target.value;
        if(targetValue && !Number.isInteger(Number(targetValue))){
            alert("数値以外は入力できません")
            return;
        }
        setState(prev=>({
            ...prev,
            [mainProjectName]:{
                ...prev?.[mainProjectName],
                [eachProjectName]:targetValue
            }
        }))
    }


    // 入力された部数が変化したとき
    const onAssignedInputChange=({e,assignId,subProjectId=null,mainProjectName,trIndex,indexInMaps,index})=>{
        const target=e.currentTarget.value;
        if(target && !Number.isInteger(Number(target))){
            alert("数値以外は入力できません")
            return;
        }

        // 変化したinput要素をfocus(indexは併配の数)
        inputRefs.current[mainProjectName][trIndex][indexInMaps][index]?.focus();

        // 変化した値に枠線を記入するための操作
        setChangedData(prev=>({
            ...prev,
            [mainProjectName]:{
                ...(prev?.[mainProjectName] || {}),
                [assignId]:[
                    ...(prev?.[mainProjectName] || {}),
                    (subProjectId ?? "main")
                ]
            }
    }));


        // input要素のvalueの更新
        // 変化したところのみしか変わらないようにする
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


    // 報告書の入力時にエンターボタンが押されたとき
    // 後日、何かしら機能を入れる可能性あり
    const onInputKeyDown=(e,trIndex,indexInMaps,index)=>{
        // if(e.key=="Enter"){
        //     // refを縦に移動

        // }
    }

    // 他の案件も同じ値を挿入する時
    const onSetOtherProjectToSameValueClick=(e,mainProjectName,projectId,index)=>{
        e.preventDefault()
        // 外注定義
        applyOtherProjectToSameValueClick({mainProjectName,projectId,index,inputValues,setInputValues,assignWithRecords,selectedDate})
    }


    // 決定ボタンを押した際は確認ページを表示する
    const onSubmitBtnClick=(e,tableSets)=>{
            e.preventDefault();

            // mainだけ、subだけが記入されている空欄があれば間違いないかチェック
            if(!confirmMainOrSubOnlyInput({assignWithRecords,date,inputValues})){
                return;
            }


            // 投稿データは１：メインはassignIdで案件に関わらずいける。２：サブはassignIdに紐づいたplanIdからmainIdを検索可能(その中で、そのプロジェクトidと合うものを選択。sameProjectFlagが違えばidは別。roundNumberは必ず1意に決まる)
            // そのため、[assignId:...,mainCount:...,subCounts:[projectId:...,subCount:...]の入れ子この配列にする
            const dataForForm=[];
            Object.entries(inputValues).forEach((eachInputValue,index)=>{
                Object.entries(eachInputValue[1]).forEach(eachSets=>{
                    const eachMainId=eachSets[0];
                    const eachCount=eachSets[1];
                    // メインはassignedIdで取得、サブはそのassignのplan_idのidをmain_idに持つproject_idで取得。
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
                ...data,
                "reportData":dataForForm
            });
            setIsConfirm(true);
    }


    // 確認OKの時
    const onConfirmOkClick=()=>{
        // バリデーション対策にinputデータを初期化はしないでおく

        // ポスト(代替記入、編集とも同じ)
        post(route("branch_manager.complete_report"));

        // バリデーション失敗した時に備えてconfirmはチェンジ
        setIsConfirm(false)
    }

    // 確認キャンセルの時
    const onConfirmCancelClick=()=>{
        // 投稿データの初期化(inputデータは持っておく)
        setData();
        // UIを戻す
        setIsConfirm(false);
    }

    return {onIssuedOrReturnedCountsChange,onAssignedInputChange,onInputKeyDown,onSetOtherProjectToSameValueClick,onSubmitBtnClick,onConfirmOkClick,onConfirmCancelClick}
}
