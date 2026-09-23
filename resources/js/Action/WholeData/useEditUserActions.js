import React from "react";
import { route } from "ziggy-js";
import disappearValidation from "../../Support/Common/disappearValidation";
import isSubmitDataEqualToDefaults from "../../Support/Common/isSubmitDataEqualToDefaults";

export default function useEditUserActions({setData,post,setValidationHidden,setSelectedPlaceName,setStaffName,isReset,setIsReset,isInWorkChange,setIsInWorkChange,userInformation}) {

    // データが入ったらform投稿
    React.useEffect(()=>{
        if(!data.changedData || Object.keys(data.changedData).length==0){
            return;
        }
        post(route("whole_data."))
    },[data])

    // エラーの削除(内部でuseEffect使用)
    disappearValidation({errors,setValidationHidden})

    // 営業所名変更
    const onPlaceNameChange=(e)=>{
        const targetValue=e.target.value;
        setSelectedPlaceName(targetValue)
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
        const changedData=isSubmitDataEqualToDefaults({defaultData:userInformation,dataInState:{
            //stateの値を渡す


        }})

        // 変化のあったものをformに格納
        setData(prev=>({
            ...prev,
            changedData //変化がなければ何も記入されない
        }))
    }

    return {onPlaceNameChange,onStaffNameChange,onIsResetChange,onIsInWorkChange,onSubmitBtnClick};
}
