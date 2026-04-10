import {route} from 'ziggy-js';
import React from 'react';
import useHandleAssignChangeInMaps from './Handle/useHandleAssignChangeInMaps';
import useHandleAssignChangeInTowns from './Handle/useHandleAssignChangeInTowns';
import useUIChange from './UI/useUIChange';

export default function useAssignProjectToStaffActions(dateSets,projectsAndTowns,assignPlan,setAssignPlan,selectedMainProject,setSelectedMainProject,setNeedNumber,setSelectedMapNumber,setSelectedDate){

  //useReducerで定義する

 // 初期設定
React.useEffect(()=>{

// 表示するdateを翌日に(0番目は今日、翌日は1番目)
 setSelectedDate(Object.keys(dateSets)[1]);

},[]);

// UIの変化(formを伴わない)
const [onSelectedDateChange,onSelectedMainProjectChange,onChangeMapOrTown]=useUIChange(setSelectedDate,setSelectedMainProject,setNeedNumber);

 // mapにスタッフの選択が変わった時(formを伴う)
const handleAssignChangeInMaps=(e,mapNumber)=>{
    useHandleAssignChangeInMaps(e,mapNumber,projectsAndTowns,selectedMainProject,setSelectedMapNumber,setAssignPlan,assignPlan)
}

//  町目にスタッフの選択が変わった時(formを伴う)
const handleAssignChangeInTowns=(e,planId)=>{
    useHandleAssignChangeInTowns(e,planId,projectsAndTowns,selectedMainProject,setAssignPlan,assignPlan)
}


  // 決定ボタンを押した時
  const onSubmitBtnClick=(e)=>{
        e.preventDefault();
    // バリデーションはlaravelに任せる(遷移しないため)
       post(route("branch_manager.assign_project"));
  }

  return{onSubmitBtnClick,
    onSelectedDateChange,
    onSelectedMainProjectChange,
    onChangeMapOrTown,
    handleAssignChangeInMaps,handleAssignChangeInTowns}
}
