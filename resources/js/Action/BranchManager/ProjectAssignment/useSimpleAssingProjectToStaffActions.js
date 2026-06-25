import {route} from 'ziggy-js';
import React from 'react';
import useHandleDataChange from './DataInput/useHandleDateChange';
import popUpPositionSeeting from '../../../Support/Common/popUpPositionSetting';
import SimpleFormatDataByProjects from './DataConfirm/SimpleFormatDataByProjects';
import SimpleDataFormatToFormData from './DataConfirm/SimpleDataFormatToFormData';

export default function useSimpleAssignProjectToStaffActions({data,setData,post,clearErrors,staffs,isConfirm,setIsConfirm,selectedDate,setSelectedDate,choicedMap,setChoicedMap,choicedByProjects,setChoicedByProjects,planIdsAndMapsByMainProjects,staffInChoice,setStaffInChoice,setPopUpVisible}){

//useReducerで定義する


// 確認と投稿の値が得られた時に確認ページを表示する
React.useEffect(()=>{
    if(!choicedByProjects || Object.keys(choicedByProjects).length == 0 || !data?.date || data.date!=selectedDate){
        setIsConfirm(false);
        return;
    }

    // 確認ページの表示
    setIsConfirm(true);
},[data,choicedByProjects])


// 日付が設定された時に、choicedMapの日付の初期値の設定
React.useState(()=>{
    if(!selectedDate){
        return;
    }
    // 選択中の地図の日付の部分のみ記入
    setChoicedMap({[selectedDate]:{}})
},[selectedDate])

React.useEffect(()=>{
    if(!staffInChoice) return;
    // 選択スタッフが決定すれば、ポップアップ
    setPopUpVisible(true)
},[staffInChoice])

// // 日付の変更
const onSelectedDateChange=(e)=>{
    useHandleDataChange(setSelectedDate,e);
}

// // 日付の初期化(全データが初期化される)
const onClickDateReset=()=>{
    if(!confirm("入力中のデータは初期化されます。\nよろしいですか？")){
        return;
    }
    setSelectedDate("");
    setChoicedMap("");
}


// 地図番号の選択がクリックされたとき
const onMapChoiceClick=(e,staff)=>{
    // まずはスタッフを反映
    setStaffInChoice(staff)
    // クリックポイントが必要なので、先に位置のみ指定(staffに連動してvisibleにする)
    popUpPositionSeeting(e,"popUpForMultiSort",0,-200)
}

// 地図番号の選択決定
const onMapDecide=(e,projectName,roundNumber)=>{
    const target=e.currentTarget;
    const targetValue=target.value;

// valueが配列に入っていれば配列から外し、入っていなければ新たに加える
// 現在の値を値渡しする
let nowMapNumberArray=choicedMap?.[selectedDate]?.[staffInChoice]?.[projectName]?.[roundNumber] || [];

const newMapNumberArray=nowMapNumberArray.includes(targetValue) ? nowMapNumberArray.filter(eachNumber=>targetValue!=eachNumber):[...nowMapNumberArray,targetValue]

    setChoicedMap(prev=>({...prev,
    [selectedDate]:({
    ...prev?.[selectedDate],
    [staffInChoice]:{
        ...prev?.[selectedDate]?.[staffInChoice],
        [projectName]:{
            ...prev?.[selectedDate]?.[staffInChoice]?.[projectName],
            [roundNumber]:newMapNumberArray
    }
    }})
}))
}

const onMapChoiceClose=()=>{
    setPopUpVisible(false);
    setStaffInChoice("");
}

   // 決定ボタンを押した際は値を変換し、その後に確認ページへ
  const onSubmitBtnClick=(e)=>{
        e.preventDefault();

        // プロジェクトごとに地図をリスト化し、選んだスタッフを載せる確認
        let newChoicedByProject=SimpleFormatDataByProjects({planIdsAndMapsByMainProjects,staffs,selectedDate,choicedMap})
        setChoicedByProjects(newChoicedByProject);

        // 送信フォームに入れる日付とスタッフに対応するplanIdsのセット
        let planIdsByStaffArrayForForm=SimpleDataFormatToFormData({planIdsAndMapsByMainProjects,choicedMap,selectedDate})

        // 送信フォームに設定
        setData({"date":selectedDate,"allData":planIdsByStaffArrayForForm})

 }

//   確認後OKのボタンが押されたとき
  const onConfirmOkClick=(e)=>{
      e.preventDefault();

      // バリデーションはlaravelに任せる(遷移しないため)
      post(route("branch_manager.simple_assign_staff_post"));
}

// 確認キャンセルの時(重複時も同じ)
const onConfirmCancelClick=(e)=>{

    e.preventDefault();

    // エラー後にやり直す際のことを考慮
    clearErrors();

    setData({});
    setChoicedByProjects({});
    // UIを投稿に戻す処理はstateの内部で行われる
  }

// 重複確認でOKなとき
 const onDuplicatedOkClick=(e)=>{
    e.preventDefault();
    // sqlのimportテーブルから移行
    post(route("branch_manager.store_including_duplicated_plans"));
 }

  return{onSelectedDateChange,onClickDateReset,onMapChoiceClick,onMapDecide,onMapChoiceClose,onSubmitBtnClick,onConfirmOkClick,onConfirmCancelClick,}
}
