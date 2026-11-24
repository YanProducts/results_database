import React from "react";
import confirmMainOrSubOnlyInput from "./Part/confirmMainOrSubOnlyInput";
import useReportCommonActions from "../Share/useReportCommonActions";

export default function useWriteReportActions({data,assignDataToStaff,projectOnlyAssignedDates,inputValues,setInputValues,inputRefs,selectedDate,setSelectedDate,setIssuedCount,setReturnedCount,setIsConfirm,setData,post,setChoicedMap,setChoiceFinishedMapProjectLists,setIsBigMedia,errors,clearErrors}){

    //共通部分(editと)
    const{onIssuedOrReturnedCountsChange,onAssignedInputChange,onInputKeyDown,onSetOtherProjectToSameValueClick,onMapNumberChange,onMapNumberChoiceDecide,onMapNumberChoiceReset,onConfirmOkClick,onConfirmCancelClick}=useReportCommonActions({assignDataToStaff,inputValues,setInputValues,inputRefs,selectedDate,setIssuedCount,setReturnedCount,setIsConfirm,setData,post,setChoicedMap,setChoiceFinishedMapProjectLists,setIsBigMedia,errors,clearErrors,postRoute:"field_staff.write_report_post",isEdit:false,changedData:{},setChangedData:()=>{}});


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


    // 日付の変更
    const onSelectedDateChange=(e)=>{
        // UI
        setSelectedDate(e.currentTarget.value)
    }

    // 決定ボタンを押した際は確認ページを表示する(editとの差が大きいため共通化しない)
    const onSubmitBtnClick=(e,tableSets)=>{
            e.preventDefault();

            // mainだけ、subだけが記入されている空欄があれば間違いないかチェック
            if(!confirmMainOrSubOnlyInput({assignDataToStaff,selectedDate,inputValues,projectOnlyFlag:projectOnlyAssignedDates.includes(selectedDate)})){
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


    return {onSelectedDateChange,onIssuedOrReturnedCountsChange,onAssignedInputChange,onInputKeyDown,onMapNumberChange,onMapNumberChoiceDecide,onMapNumberChoiceReset,onSetOtherProjectToSameValueClick,onSubmitBtnClick,onStartOverClick,onConfirmOkClick,onConfirmCancelClick}

}
