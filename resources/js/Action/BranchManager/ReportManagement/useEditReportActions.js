import React from "react";
import applyOtherProjectToSameValueClick from "../../Share/applyOtherProjectToSameValueClick";
import useTargetChangeHandler from "./Part/useTargetChangeHandler";
import confirmMainOrSubOnlyInput from "../../FieldStaff/Part/confirmMainOrSubOnlyInput";
import { router } from "@inertiajs/react";

// 報告書編集の動き
export default function useEditReportActions({date,assignWithRecords,inputValues,setInputValues,inputRefs,changedData,setChangedData,setIsConfirm,data,setData,post,setIsBigMedia}){


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

        // 変化した値にcssを記入するための操作
        // すでに存在している場合には二重にstateが動くのを防ぐために除外
        useTargetChangeHandler({changedData,setChangedData,mainProjectName,assignId,subProjectId});

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

    // もう１度日付選択から戻るとき
    const onStartOverClick=()=>{
        router.visit(route("branch_manager.choice_report_target"));
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
        applyOtherProjectToSameValueClick({mainProjectName,projectId,index,inputValues,setInputValues,assignDataToStaff:assignWithRecords,selectedDate:date,isEdit:true,changedData,setChangedData})
    }


    // 決定ボタンを押した際は確認ページを表示する
    const onSubmitBtnClick=(e,tableSets)=>{
            e.preventDefault();

            // mainだけ、subだけが記入されている空欄があれば間違いないかチェック
            if(!confirmMainOrSubOnlyInput({assignDataToStaff:assignWithRecords,selectedDate:date,inputValues,isEdit:true})){
                return;
            }

            // 投稿データは１：メインはassignIdで案件に関わらずいける。２：サブはassignIdに紐づいたplanIdからmainIdを検索可能(その現場、そのスタッフにaasignされたIdは一意に決まる。データがる場合(2日トータル)はLaravelで更新。roundNumberが違ってもプロジェクトId同じ)
            // そのため、[assignId:...,mainCount:...,subCounts:[projectId:...,subCount:...]の入れ子この配列にする
            const dataForForm=[];
            Object.entries(inputValues).forEach((eachInputValue,index)=>{

                for (const eachSets of Object.entries(eachInputValue[1])){
                    const eachMainId=eachSets[0]; //assignId
                    const eachCount=eachSets[1]; //それぞれの町目の配布数のデータ

                    // そのメイン案件自体が変化していないものはデータにpushしない
                    if(!changedData?.[eachInputValue[0]]?.[eachMainId]){
                        continue;
                    }

                    const {main,...subSets}=eachCount;

                    // mainProjectNameは取得せずともassignIdで投稿時には紐付け可能
                    // メインはassignedIdで取得、サブはそのassignのplan_idのidをmain_idに持つproject_idで取得。
                    dataForForm.push({
                        "assignId":eachMainId,
                        // メイン案件が更新されていない場合は投稿しない
                        ...((changedData?.[eachInputValue[0]]?.[eachMainId]).includes("main") ? {"mainCount":eachCount.main ?? 0} : {}),
                        // それぞれのサブ案件が更新されていない場合は投稿しない
                        "subData":
                            Object.entries(subSets).map((IdCountSets)=>
                              (changedData?.[eachInputValue[0]]?.[eachMainId]).map(n=>Number(n)).includes(Number(IdCountSets[0])) ? {"projectId":IdCountSets[0],"subCount":IdCountSets[1]} : null
                            ).filter(obj=>obj!=null) //併配が記入されていない場合(メインや他の併配物のみ記入)はnullで除去
                    })
                }
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

    return {onIssuedOrReturnedCountsChange,onAssignedInputChange,onStartOverClick,onInputKeyDown,onSetOtherProjectToSameValueClick,onSubmitBtnClick,onConfirmOkClick,onConfirmCancelClick}
}
