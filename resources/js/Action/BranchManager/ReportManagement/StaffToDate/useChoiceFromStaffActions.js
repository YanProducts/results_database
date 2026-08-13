import React from "react";

// スタッフから選択する場合の動き
export default function useChoiceFromStaffActions({data,setData,selectedStaffs,setSelectedStaffs,post}){

    React.useEffect(()=>{
        // フォームデータが空の場合はアウト
        if(typeof(data)!="object" || Object.keys(data).length==0){
            return;
        }

        post(route("branch_manager.choice_report_target_post"));
    },[data])

    // 選択スタッフリストの変化(複数可)
    const onStaffListsChange=(e)=>{
        const target=e.currentTarget;
        const numberTargetValue=Number(target.value)
        if(selectedStaffs.includes(numberTargetValue)){
            setSelectedStaffs(selectedStaffs.filter(eachStaff=>eachStaff!=numberTargetValue));
        }else{
            setSelectedStaffs([...selectedStaffs,numberTargetValue]);
        }
    }

    // 選択スタッフの決定
    const onSubmitBtnClick=(e)=>{
        e.preventDefault();
        // 未選択の場合は戻す
        if(selectedStaffs.length==0){
            alert("スタッフが選択されていません");
            return;
        }
        // フォームデータに入れる
        setData({"staffs":selectedStaffs});
    }

    return{onStaffListsChange,onSubmitBtnClick}
}
