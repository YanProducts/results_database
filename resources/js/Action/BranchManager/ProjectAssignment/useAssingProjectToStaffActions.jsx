import {route} from 'ziggy-js';
import React from 'react';
import useHandleAssignChangeInMaps from './formSets/useHandleAssignChangeInMaps';
import useHandleAssignChangeInTowns from './formSets/useHandleAssignChangeInTowns';
import useUIChange from './UI/useUIChange';
import { useHandleChangeMapOrTown } from './formSets/useHandleChangeMapOrTown';

export default function useAssignProjectToStaffActions(dateSets,projectsAndTowns,assignPlan,setAssignPlan,selectedMainProject,setSelectedMainProject,needNumber,setNeedNumber,selectedMapNumber, setSelectedMapNumber,setSelectedDate,setIsConfirm){


 //useReducerで定義する

 // 初期設定
React.useEffect(()=>{
// 表示するdateを翌日に(0番目は今日、翌日は1番目)
 setSelectedDate(Object.keys(dateSets)[1]);
},[]);



// データ入力時のUIの変化(formを伴わない)
const [onSelectedDateChange,onSelectedMainProjectChange]=useUIChange(setSelectedDate,setSelectedMainProject,setNeedNumber);

const onChangeMapOrTown=(e)=>{
    useHandleChangeMapOrTown(e,needNumber,setNeedNumber,selectedMainProject,setAssignPlan,setSelectedMapNumber)
}

 // mapにスタッフの選択が変わった時(formを伴う)
const handleAssignChangeInMaps=(e,mapNumber)=>{
    useHandleAssignChangeInMaps(e,mapNumber,projectsAndTowns,selectedMapNumber,selectedMainProject,setSelectedMapNumber,setAssignPlan,assignPlan)
}

//  町目にスタッフの選択が変わった時(formを伴う)
const handleAssignChangeInTowns=(e,planId)=>{

    useHandleAssignChangeInTowns(e,planId,selectedMainProject,setAssignPlan)
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
