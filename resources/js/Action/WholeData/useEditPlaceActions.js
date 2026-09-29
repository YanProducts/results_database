import React from "react";
import { router } from "@inertiajs/react";
import { route } from "ziggy-js";
import useClearErrors from "../Share/useClearErrors";
import formatStateData from "./EditPlace/formatStateData";

export default function useEditPlaceActions({data,setData,errors,clearErrors,placeInformation,placeName,setPlaceName,isInWorkChange,setIsInWorkChange,colors,setColors}) {

    // データが入ったらform投稿
    React.useEffect(()=>{

        // 変更がない場合はreturn()
        // キーの名前はスネークケースになることに注意(元の値がLaraelのデータベースそのままfindしているため)
        if(!Object.keys(data).some(eachKey=>["place_name","is_active","red","green","blue"].includes(eachKey))){
            return;
        }

        // 変更がある場合は投稿
        post(route("whole_data.update_place"))

        // validationで戻った時に備えてstateは戻さずにdataは戻す
        setData({
         id:placeInformation.id,
        })
    },[data])


    // エラーの削除(内部でuseEffect使用)
    useClearErrors({errors,clearErrors})

    // 営業所名変更
    const onPlaceNameChange=(e)=>{
        const targetValue=e.target.value;
        setPlaceName(targetValue)
    }

    // 色の変更
    const onColorChange=(e)=>{
        // 未実装
        setColors()
    }


    // 稼働状況変更
    const onIsActiveChange=()=>{
        setIsInWorkChange(!isInWorkChange)
    }

    // 提出
    const onSubmitBtnClick=()=>{
        // 以前と同じものをチェック
        // formatStateDataはstateのフォーマットを比較用に変更
        const changedData=isSubmitDataEqualToDefaults({defaultData:placeInformation,dataInState:formatStateData({placeInformation,placeName,colors,isInWorkChange})})

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

    return {onPlaceNameChange,onIsActiveChange,onColorChange,onSubmitBtnClick,onCancelBtnClick};
}
