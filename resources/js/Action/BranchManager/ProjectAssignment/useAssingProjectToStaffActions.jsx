import {route} from 'ziggy-js';
import React from 'react';
import useHandleAssignChangeInMaps from './DataInput/useHandleAssignChangeInMaps';
import useHandleAssignChangeInTowns from './DataInput/useHandleAssignChangeInTowns';
import { useHandleChangeMapOrTown } from './DataInput/useHandleChangeMapOrTown';
import FormatDataForFormAndView from './DataConfirm/FormatDataForFormAndView';
import useHandleDataChange from './DataInput/useHandleDateChange';
import useHandleProjectChange from './DataInput/useHandleProjectChange';

export default function useAssignProjectToStaffActions({dateSets,projectsAndTowns,staffs,assignPlan,setAssignPlan,selectedMainProject,setSelectedMainProject,needNumber,setNeedNumber,mapMeta, setMapMeta,setSelectedDate,isConfirm,setIsConfirm,setAssignPlanForConfirmView,setData}){

// 実験用
React.useEffect(()=>{
    console.log(mapMeta)
},[mapMeta])



 //useReducerで定義する

 // 初期設定
React.useEffect(()=>{
// 表示するdateを翌日に(0番目は今日、翌日は1番目)
 setSelectedDate(Object.keys(dateSets)[1]);
},[]);

// 確認表示になった時に実行
React.useEffect(()=>{
    if(!isConfirm){
        return;
    }
    // データを①form用②表示で使える用の土台に変換(この段階では、スタッフも町名もIdで格納)
    FormatDataForFormAndView({assignPlan,staffs,projectsAndTowns,setAssignPlanForConfirmView,setData,mapMeta});
},[isConfirm])


// 日付の変更
const onSelectedDateChange=(e)=>{
    useHandleDataChange(setSelectedDate,e)
}

// 案件の変更
const onSelectedMainProjectChange=(e)=>{
    useHandleProjectChange(mapMeta,setMapMeta,setSelectedMainProject,e);
}

// 表示が地図か町目か
const onChangeMapOrTown=(e)=>{
    useHandleChangeMapOrTown(e,needNumber,setNeedNumber,selectedMainProject,setAssignPlan,setMapMeta)
}

 // mapにスタッフの選択が変わった時(formを伴う)
const handleAssignChangeInMaps=(e,mapNumber)=>{
    useHandleAssignChangeInMaps(e,mapNumber,projectsAndTowns,mapMeta,selectedMainProject,setMapMeta,setAssignPlan,assignPlan)
}

//  町目にスタッフの選択が変わった時(formを伴う)
const handleAssignChangeInTowns=(e,planId)=>{
    useHandleAssignChangeInTowns(e,planId,selectedMainProject,setAssignPlan,mapMeta,setMapMeta,projectsAndTowns)
}


  // 決定ボタンを押した際は確認ページを表示する
  const onSubmitBtnClick=(e)=>{
        e.preventDefault();
        setIsConfirm(true);
 }

    //   確認後OKのボタンが押されたとき
  const onConfirmOkClick=(e)=>{
      e.preventDefault();
      // データをスタッフ⇨町目リストに並び替え
      // バリデーションはlaravelに任せる(遷移しないため)

      post(route("branch_manager.assign_project"));
  }

  const onConfirmCancelClick=(e)=>{
    e.preventDefault();
    // UIを投稿に戻す
    setIsConfirm(false);
  }

  return{onSubmitBtnClick,
    onSelectedDateChange,
    onSelectedMainProjectChange,
    onChangeMapOrTown,
    handleAssignChangeInMaps,handleAssignChangeInTowns
    ,onConfirmOkClick,onConfirmCancelClick}
}
