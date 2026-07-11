import React from "react";
import { route } from "ziggy-js";

export default function useWriteReportActions({assignDataToStaff,inputValues,setInputValues,inputRefs,selectedDate,setSelectedDate,setIssuedCount,setReturnedCount,setIsConfirm,setData,post}){


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
            "reportData":[]
        })
    },[selectedDate])


    // 日付の変更
    const onSelectedDateChange=(e)=>{
        // UI
        setSelectedDate(e.currentTarget.value)
    }

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
        // テーブルの要素
        // console.log(tableSets);
        // メイン案件
        // console.log(mainProjectName);
        // // 現在入力中の案件(メイン案件の中のどれか)
        console.log(projectId);
        // // 現在の案件がメインかどうかを見る
        // console.log(index);
        // // これが現在すでにデータにある数であり、今後反映させる数
        // console.log(inputValues);
        // 元々の渡されたプロジェクト
        console.log(assignDataToStaff)

        // 参照しないコピー
        const inputMainProjectCopy=structuredClone(inputValues[mainProjectName])

        // メイン案件の時
        if(index==0){
            // それぞれのmainProjectにおけるキーの取得(これはassignId、つまり割り当て後の案件のキー:mainのみ)
            const mainKeys=Object.keys(inputMainProjectCopy);
            mainKeys.forEach(function(mainKey){

                // メイン案件が入力されているものの取得
                if(inputMainProjectCopy?.[mainKey].main){

                    console.log("その町目におけるassignID(inputされた値のキー)")
                    console.log(mainKey)


                    console.log("inputから取得されたメイン案件における、この町目IDにおける、メイン案件のデータ=つまり入力され、これからコピーされるべき数")
                    console.log(inputMainProjectCopy?.[mainKey].main)

                    // その日、そのスタッフ、そのメイン案件に割り当てられたデータ
                    // キーにはmap番号が振られているので、データはvalueに入っている
                    Object.values(assignDataToStaff[selectedDate][mainProjectName].each_data).forEach(function(eachAssignedData){
                    console.log("Laravelから送られてきて、このスタッフ・この日・このメイン案件のデータ")
                    console.log(eachAssignedData)

                    // 今入力している町目のidにおける、Laravelから送られてきた値で参照される併配が記述された一式
                    const matchdAssignData=eachAssignedData.find(eachTownData=>eachTownData?.assign_id==mainKey)
                    console.log(matchdAssignData) //foreachされているので、それぞれに置いて出てくる
                            // ここでinputValueに繋がる値を設定する

                    })


                    // const subId="そのメイン案件で空いている併配案件の取得";
                    // inputMainProjectCopy[mainKey][subId]=inputMainProjectCopy[mainKey].main;

                }
            })

        }


    }



    // 決定ボタンを押した際は確認ページを表示する
    const onSubmitBtnClick=(e,tableSets)=>{
            e.preventDefault();

            // mainだけ、subだけが記入されている空欄があれば間違いないかチェック



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
                "date":selectedDate,
                "reportData":dataForForm
            });
            setIsConfirm(true);
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

    return {onSelectedDateChange,onIssuedOrReturnedCountsChange,onAssignedInputChange,onInputKeyDown,onSetOtherProjectToSameValueClick,onSubmitBtnClick,onStartOverClick,onConfirmOkClick,onConfirmCancelClick}

}
