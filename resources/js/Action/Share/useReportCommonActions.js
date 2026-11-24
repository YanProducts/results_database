import React from "react";
import { route } from "ziggy-js";
import useClearErrors from "./useClearErrors";
import applyOtherProjectToSameValueClick from "./applyOtherProjectToSameValueClick";
import useTargetChangeHandler from "../BranchManager/ReportManagement/Part/useTargetChangeHandler";

// 報告書の作成と編集における共通のAction
export default function useReportCommonActions({assignDataToStaff,inputValues,setInputValues,inputRefs,selectedDate,setIssuedCount,setReturnedCount,setIsConfirm,setData,post,setChoicedMap,setChoiceFinishedMapProjectLists,setIsBigMedia,errors,clearErrors,postRoute,isEdit=false,changedData={},setChangedData=()=>{}}){

    //エラー自体の削除
    useClearErrors({errors,clearErrors});

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


        // editの場合のみ
        // 変化した値にcssを記入するための操作
        // すでに存在している場合には二重にstateが動くのを防ぐために除外
        if(isEdit){
            useTargetChangeHandler({changedData,setChangedData,mainProjectName,identifier,subProjectId});
        }

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
        applyOtherProjectToSameValueClick({mainProjectName,projectId,index,inputValues,setInputValues,assignDataToStaff,selectedDate,projectOnlyFlag,changedData,setChangedData})
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

        // (案件から選択の際に)記入データがある場合は地図番号のリセット
        //この部分は後に要確認
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


        // 決定ボタンを押した際は確認ページを表示する流れはwriteとeditで違いが大きいので実装しない


        // 確認OKの時
        const onConfirmOkClick=()=>{
            // バリデーション対策にinputデータを初期化はしないでおく

            // ポスト
            post(route(postRoute));

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


        return {onIssuedOrReturnedCountsChange,onAssignedInputChange,onInputKeyDown,onSetOtherProjectToSameValueClick,onMapNumberChange,onMapNumberChoiceDecide,onMapNumberChoiceReset,onConfirmOkClick,onConfirmCancelClick}

}
