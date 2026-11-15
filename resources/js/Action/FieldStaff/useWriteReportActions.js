import React from "react";
import { route } from "ziggy-js";
import applyOtherProjectToSameValueClick from "../Share/applyOtherProjectToSameValueClick";
import confirmMainOrSubOnlyInput from "./Part/confirmMainOrSubOnlyInput";
import useClearErrors from "../Share/useClearErrors";

export default function useWriteReportActions({data,assignDataToStaff,projectOnlyAssignedDates,inputValues,setInputValues,inputRefs,selectedDate,setSelectedDate,setIssuedCount,setReturnedCount,setIsConfirm,setData,post,setChoicedMap,setChoiceFinishedMapProjectLists,isBigMedia,setIsBigMedia,errors,clearErrors}){

    //エラー自体の削除
    useClearErrors({errors,clearErrors})

    // 日付が変更されたらpost用のデータにセット(他のデータは自動的に初期化)
    //日付が初期化されたら関連するstateとformは全て戻す
    React.useEffect(()=>{
        if(!selectedDate){
            setIssuedCount(0);
            setReturnedCount(0);
            setInputValues({});
            setData({})
            return;
        }
        setData({
            "date":selectedDate,
            "reportData":[],
            // その日が案件のみからassignされているか
            "projectOnlyFlag":projectOnlyAssignedDates.includes(selectedDate),
        })
    },[selectedDate])


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


    // 日付の変更
    const onSelectedDateChange=(e)=>{
        // UI
        setSelectedDate(e.currentTarget.value)
    }

    // 持ち出し&返却のまとめ //mapNumberは必要ない
    const onIssuedOrReturnedCountsChange=(e,mainProjectName,eachProjectName,setState)=>{

        const targetValue=e.target.value;
        if (targetValue && !/^\d+$/.test(targetValue)){
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
    const onAssignedInputChange=({e,identifier,subProjectId=null,mainProjectName,trIndex,indexInMaps,index})=>{

        const target=e.currentTarget.value;
        if (target && !/^\d+$/.test(target)){
            alert("数値以外は入力できません")
            return;
        }

        // 変化したinput要素をfocus(indexは併配の数)
        inputRefs.current[mainProjectName][trIndex][indexInMaps][index]?.focus();
        // input要素のvalueの更新
        setInputValues(prev=>({
            ...prev,
            [mainProjectName]:{
                ...(prev?.[mainProjectName] || {}),
                [identifier]:{
                    ...(prev?.[mainProjectName]?.[identifier] || {}),
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
    const onSetOtherProjectToSameValueClick=(e,mainProjectName,projectId,index,projectOnlyFlag)=>{
        e.preventDefault()
        // 外注定義
        applyOtherProjectToSameValueClick({mainProjectName,projectId,index,inputValues,setInputValues,assignDataToStaff,selectedDate,projectOnlyFlag})
    }

    // (案件から選択の際に)地図番号選択の変化時
    const onMapNumberChange=({e,mainProjectName})=>{

        const targetValue=e.currentTarget.value;

        // 表示地図番号の変化
        setChoicedMap(prev=>({
            ...prev,
            [mainProjectName]:
                prev[mainProjectName] ?
                (prev[mainProjectName].map(eachSelectedMap=>String(eachSelectedMap)).includes(String(targetValue)) ?prev[mainProjectName].filter(eachMap=>String(eachMap)!=String(targetValue))
                :[...prev[mainProjectName],targetValue])
                :[targetValue]
        }))
    }

    // (案件のみから選択の際に)一時的に表示用のマップの選択が完了している地図のリスト
    const onMapNumberChoiceDecide=(mainProjectName)=>{

        // 何も選択されていない場合にアラートを出して戻りはしない。なぜなら、stateが反映されていないだけの可能性もあるのが1つ目、 何も選択しないでの選択もある。なぜなら他の人がフォローしたり翌日に回した可能性が2つ目の理由

        // stateの遅延で二重送信を考慮
        setChoiceFinishedMapProjectLists(prev=>prev.includes(mainProjectName) ? prev : [...prev,mainProjectName]
        )
    }

    // (案件から選択の際に)地図番号のリセット
    const onMapNumberChoiceReset=(mainProjectName)=>{
        // 最初の選択もしくはその案件が空の時はそのまま初期化、すでに選択した状態からマップの変更もしくは次のマップ選択を行う時は「前のマップにおける案件データを削除するか」を確認する
        // ここの仕様、意見次第で変わる可能性あり
        if(Object.keys(inputValues[mainProjectName] ?? {}).length && confirm("案件のデータを初期化しますか？")){
            // そのほかのキーの情報を残したまま、特定のキーのセットを削除(町目ごと配布数、持ち出し、返却)
            //オブジェクトで特定のキーの削除にはdeleteを使用
            setInputValues((prev)=>{
                const copyPrev={...prev}
                delete copyPrev[mainProjectName];
                return copyPrev;
            })
            setIssuedCount((prev)=>{
                const copyPrev={...prev}
                delete copyPrev[mainProjectName];
                return copyPrev;
            })
            setReturnedCount((prev)=>{
                const copyPrev={...prev}
                delete copyPrev[mainProjectName];
                return copyPrev;
            })
        }
        // その地図の町目リストの表示を隠す
        // //初期状態は以前の状態を残す
        setChoiceFinishedMapProjectLists(prev=>prev.filter(eachMainProject=>eachMainProject!=mainProjectName))
    }

    // 決定ボタンを押した際は確認ページを表示する
    const onSubmitBtnClick=(e,tableSets)=>{
            e.preventDefault();

            // mainだけ、subだけが記入されている空欄があれば間違いないかチェック
            if(!confirmMainOrSubOnlyInput({assignDataToStaff,selectedDate,inputValues})){
                return;
            }

            // 投稿データは１：メインはassignId(projectOnlyはplanId):双方でidentifierで案件に関わらずいける。２：サブは(assignIdに紐づいた)planIdからmainIdを検索可能。
            // そのため、[identifier(assignIdもしくはplanId):...,mainCount:...,subCounts:[projectId:...,subCount:...]の入れ子この配列にする
            const dataForForm=[];
            Object.entries(inputValues).forEach((eachInputValue,index)=>{
                Object.entries(eachInputValue[1]).forEach(eachSets=>{
                    const eachMainId=eachSets[0];
                    const eachCount=eachSets[1];
                    // メインはassignedId/planIdで取得、サブは(そのassignの)plan_idのidをmain_idに持つproject_idで取得。
                    dataForForm.push({
                        "identifier":eachMainId,
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
                "reportData":dataForForm,
                "projectOnlyFlag": projectOnlyAssignedDates.includes(selectedDate),
            });
            setIsConfirm(true);
            clearErrors();

    }

    // 日付選択からやり直す時
    const onStartOverClick=()=>{
        // まずは日付を初期化=その後に関連stateを初期化
        setSelectedDate("");
    }

    // 確認OKの時
    const onConfirmOkClick=()=>{
        // バリデーション対策にinputデータを初期化はしないでおく

        // ポスト
        post(route("field_staff.write_report_post"));

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

    return {onSelectedDateChange,onIssuedOrReturnedCountsChange,onAssignedInputChange,onInputKeyDown,onMapNumberChange,onMapNumberChoiceDecide,onMapNumberChoiceReset,onSetOtherProjectToSameValueClick,onSubmitBtnClick,onStartOverClick,onConfirmOkClick,onConfirmCancelClick}

}
