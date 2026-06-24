import {route} from 'ziggy-js';
import React from 'react';
import useHandleAssignChangeInMaps from './DataInput/useHandleAssignChangeInMaps';
import useHandleAssignChangeInTowns from './DataInput/useHandleAssignChangeInTowns';
import { useHandleChangeMapOrTown } from './DataInput/useHandleChangeMapOrTown';
import FormatDataForFormAndView from './DataConfirm/FormatDataForFormAndView';
import useHandleDataChange from './DataInput/useHandleDateChange';
import useHandleProjectChange from './DataInput/useHandleProjectChange';
import popUpPositionSeeting from '../../../Support/Common/popUpPositionSetting';

export default function useSimpleAssignProjectToStaffActions({data,post,isConfirm,selectedDate,setSelectedDate,choicedMap,setChoicedMap,planIdsAndMapsByMainProjects,setStaffInChoice,setPopUpVisible}){

//useReducerで定義する


// 確認表示になった時に実行
React.useEffect(()=>{
    if(!isConfirm){
        return;
    }
    // データを①form用②表示で使える用の土台に変換(この段階では、スタッフも町名もIdで格納)
    // FormatDataForFormAndView({assignPlan,staffs,selectedDate,projectsAndTowns,setAssignPlanForConfirmView,setData,mapMeta});
},[isConfirm])


// 日付が設定された時に、choicedMapの日付の初期値の設定
React.useState(()=>{
    if(!selectedDate){
        return;
    }
    // 選択中の地図の日付の部分のみ記入
    setChoicedMap({[selectedDate]:{}})
},[selectedDate])


// // 日付の変更
const onSelectedDateChange=(e)=>{
    useHandleDataChange(setSelectedDate,e);
}

// // 日付の初期化(全データが初期化される)
const onClickDateReset=()=>{
    if(!confirm("入力中のデータは初期化されます。\nよろしいですか？")){
        return;
    }
    setSelectedMainProject("");
    setSelectedDate("");
    setChoicedMap("");
}


// 地図番号の選択がクリックされたとき
const onMapChoiceClick=(e,staff)=>{
    setStaffInChoice(staff)
    setPopUpVisible(true)
    popUpPositionSeeting(e,"popUpForMultiSort",-5,-10)
}

// 地図番号の選択決定
const onMapDecide=(e,choicedStaff,projectName,roundNumber)=>{
    const target=e.currentTarget;
    const targetValue=target.value;


    // valueが配列に入っていれば配列から外し、入っていなければ新たに加える
    // 現在の値を値渡しする
    let nowMapNumberArray=choicedMap?.[selectedDate]?.[choicedStaff]?.[projectName]?.[roundNumber]?.map_number || [];


const newMapNumberArray=nowMapNumberArray.includes(targetValue) ? nowMapNumberArray.filter(eachNumber=>targetValue!=eachNumber):[...nowMapNumberArray,targetValue]

    console.log(newMapNumberArray)



    setChoicedMap(prev=>({...prev,
    [selectedDate]:({
    ...prev?.[selectedDate],
    [choicedStaff]:{
        ...prev?.[selectedDate]?.[choicedStaff],
        [projectName]:{
            ...prev?.[selectedDate]?.[choicedStaff]?.[projectName],
            [roundNumber]:[
                ...prev?.[selectedDate]?.[choicedStaff]?.[projectName]?.[roundNumber] ?? [],
                ...newMapNumberArray
            ]


    }
    }})
}))
}

const onMapChoiceClose=()=>{
    setPopUpVisible(false);
    setStaffInChoice("");
}


   // 決定ボタンを押した際は確認ページを表示する
  const onSubmitBtnClick=(e)=>{
        e.preventDefault();
        setIsConfirm(true);
 }

     //   確認後OKのボタンが押されたとき
//   const onConfirmOkClick=(e)=>{
//       e.preventDefault();

      // 割り当てのないスタッフの確認
//     const nonAssignedStaffs=Object.keys(staffs).filter(staffId=>data.allData.some(eachData=>eachData.staffId==staffId && eachData.planIds.length==0));
//     if(nonAssignedStaffs.length>0 && !confirm("以下のスタッフが割り当てられていません。\nよろしいですか？\n\n" + nonAssignedStaffs.map(eachNonAssignedStaffId=>("・" + staffs[eachNonAssignedStaffId])).join("\n"))){
//         return;
//     }

//       // データをスタッフ⇨町目リストに並び替え
//       // バリデーションはlaravelに任せる(遷移しないため)
//       post(route("branch_manager.assign_staff_post"));

//      // 重複確認で戻ってきたとき
//      if(flash?.duplicated){
//         setDuplicatedCheck(true)
//     }

// }

// // 確認キャンセルの時(重複時も同じ)
// const onConfirmCancelClick=(e)=>{
//     e.preventDefault();

//     // エラー後にやり直す際のことを考慮
//     clearErrors();

//     // 重複確認後の可能性も考慮して重複確認も戻す
//     if(duplicatedCheck){
//         setDuplicatedCheck(false)
//     }
//     // UIを投稿に戻す
//     setIsConfirm(false);
//   }

// // 重複確認でOKなとき
//  const onDuplicatedOkClick=(e)=>{
//     e.preventDefault();
//     // sqlのimportテーブルから移行
//     post(route("branch_manager.store_including_duplicated_plans"));
//  }

  return{onSelectedDateChange,onClickDateReset,onMapChoiceClick,onMapDecide,onMapChoiceClose,onSubmitBtnClick}
}
