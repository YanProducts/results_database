import { router } from "@inertiajs/react";
import { route } from "ziggy-js";
import confirmMainOrSubOnlyInput from "../../FieldStaff/Part/confirmMainOrSubOnlyInput";
import useReportCommonActions from "../../Share/useReportCommonActions";

// 報告書編集の動き
export default function useEditReportActions({prefix,date,errors,assignWithRecords,inputValues,setInputValues,setIssuedCount,setReturnedCount,setChoicedMap,setChoiceFinishedMapProjectLists,inputRefs,changedData,setChangedData,setIsConfirm,data,setData,post,setIsBigMedia,clearErrors}){


    // writeと共通
    const{onIssuedOrReturnedCountsChange,onAssignedInputChange,onInputKeyDown,onSetOtherProjectToSameValueClick,onMapNumberChange,onMapNumberChoiceDecide,onMapNumberChoiceReset,onConfirmOkClick,onConfirmCancelClick}=useReportCommonActions({assignDataToStaff:assignWithRecords,inputValues,setInputValues,inputRefs,selectedDate:date,setIssuedCount,setReturnedCount,setIsConfirm,setData,post,setChoicedMap,setChoiceFinishedMapProjectLists,setIsBigMedia,errors,clearErrors,postRoute:prefix=="branch_manager" ? "branch_manager.complete_report_post" : "field_staff.edit_report_post",isEdit:true,changedData,setChangedData});

    // もう１度日付選択から戻るとき(prefixにより分ける)
    const onStartOverClick=()=>{
        router.visit(route(prefix === "branch_manager" ? "branch_manager.choice_report_target": "field_staff.overview_reports"));
    }

    // 決定ボタンを押した際は確認ページを表示する
    //write側と相違点が多いため別保持する
    const onSubmitBtnClick=(e,tableSets)=>{
            e.preventDefault();

            // mainだけ、subだけが記入されている空欄があれば間違いないかチェック


            //projectOnlyFlagあとで入れること！
            if(!confirmMainOrSubOnlyInput({assignDataToStaff:assignWithRecords,selectedDate:date,inputValues,
            projectOnlyFlag:projectOnlyFlag,isEdit:true})){
                return;
            }

            // 投稿データは１：メインはassignIdで案件に関わらずいける。２：サブはassignIdに紐づいたplanIdからmainIdを検索可能(その現場、そのスタッフにaasignされたIdは一意に決まる。データがる場合(2日トータル)はLaravelで更新。roundNumberが違ってもプロジェクトId同じ)
            // そのため、[assignId:...,mainCount:...,subCounts:[projectId:...,subCount:...]の入れ子この配列にする
            const dataForForm=[];
            Object.entries(inputValues).forEach((eachInputValue,index)=>{

                for (const eachSets of Object.entries(eachInputValue[1])){
                    const eachMainId=eachSets[0]; //assignId(通常)/planId(projectOnly)
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
            clearErrors();
    }

    return {onIssuedOrReturnedCountsChange,onAssignedInputChange,onStartOverClick,onInputKeyDown,onSetOtherProjectToSameValueClick,onMapNumberChange,onMapNumberChoiceDecide,onMapNumberChoiceReset,onSubmitBtnClick,onConfirmOkClick,onConfirmCancelClick}
}
