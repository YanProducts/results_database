import React, { use } from "react";
import { route } from "ziggy-js";
import { router } from "@inertiajs/react";
import useClearErrors from "../Share/useClearErrors";
import isSubmitDataEqualToDefaults from "../../Support/Common/isSubmitDataEqualToDefaults";
import formatStateData from "./EditUser/formatStateData";

export default function useEditUserActions({data,setData,errors,clearErrors,post,setValidationHidden,selectedPlaceId,setSelectedPlaceId,staffName,setStaffName,isReset,setIsReset,isInWorkChange,setIsInWorkChange,userInformation}) {

    // データが入ったらform投稿
    React.useEffect(()=>{

        // 変更がない場合はreturn
        if(!Object.keys(data).some(eachKey=>["isInWorkChange","staffName","placeId","registered"].includes(eachKey))){
            return;
        }

        // 変更がある場合は投稿
        post(route("whole_data.update_user"))

        // validationで戻った時に備えてstateは戻さずにdataは戻す
        setData({
         id:userInformation.id,
         role:userInformation.role
        })
    },[data])


    // エラーの削除(内部でuseEffect使用)
    useClearErrors({errors,clearErrors})

    // 所属営業所変更
    const onPlaceNameChange=(e)=>{
        const targetValue=e.target.value;
        setSelectedPlaceId(targetValue)
    }

    // スタッフ名変更
    const onStaffNameChange=(e)=>{
        const targetValue=e.target.value;
        setStaffName(targetValue)
    }

    // パスワードリセット変更
    const onIsResetChange=()=>{
        setIsReset(!isReset)
    }

    // 稼働状況変更
    const onIsInWorkChange=()=>{
        setIsInWorkChange(!isInWorkChange)
    }

    // 提出
    const onSubmitBtnClick=()=>{
        // 以前と同じものをチェック
        // formatStateDataはstateのフォーマットを比較用に変更
        const changedData=isSubmitDataEqualToDefaults({defaultData:userInformation,dataInState:formatStateData({isInWorkChange,staffName,selectedPlaceId,isReset,userInformation})})

        // 変化のあったものをformに格納
        setData(prev=>({
            ...prev,
            ...changedData //変化がなければ何も記入されないので、useEffectは1つ目の条件分岐でreturnされる(アラートはisSubmitData...のメソッドの内部)
        }))
    }

    // 全体確認のページに戻る
    const onCancelBtnClick=()=>{
        router.get(route("whole_data.admin_overview",{
            "type":"all"
        }))
    }

    return {onPlaceNameChange,onStaffNameChange,onIsResetChange,onIsInWorkChange,onSubmitBtnClick,onCancelBtnClick};
}

