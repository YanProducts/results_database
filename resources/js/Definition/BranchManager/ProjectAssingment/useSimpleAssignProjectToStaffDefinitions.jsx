import { useForm } from "@inertiajs/react";
import React from "react";

export default function useSimpleAssignProjectToStaffDefinitions(){

  // フォーム
  const { data, setData, post, processing, errors,clearErrors, reset}=useForm({
        // 送信はバリデーションのしやすさも含め、[staff_id=>[plan_idの配列]]という配列
  });

  // フォームに送信する前段階では、planIdをキーにし、スタッフIdを配列にして保存(変更がしやすいため)
  const [assignPlan,setAssignPlan]=React.useState({});

  // 確認表示か投稿表示か
  const [isConfirm,setIsConfirm]=React.useState(false);

  // 重複確認表示
  const [duplicatedCheck,setDuplicatedCheck]=React.useState(false);

  // 表示する日付(選択中のY-m-d型の文字列を返す)
  const [selectedDate,setSelectedDate]=React.useState("");

// 現在選択中のmap...date=>{staff:[{"project_name","map_number"},{},{}...]}の形式で捕捉
const [choicedMap,setChoicedMap]=React.useState({})

//   popUpを表示するか
const [popUpVisible,setPopUpVisible]=React.useState(false);

// 地図番号を選択中のスタッフ
const [staffInChoice,setStaffInChoice]=React.useState("");

//  // mapにスタッフを割り当てた場合の表示用のstate。mainProject:{mapNumber:スタッフ名}の形式
//   const [mapMeta,setMapMeta]=React.useState({});


  //   確認用のviewに表示するための配列
//   const [assignPlanForConfirmView,setAssignPlanForConfirmView]=React.useState({});

   // ページの横幅
   const [pageMinWidth,pageMaxWidth]=["min-w-200","max-w-300"];


  return { data, setData, post, processing, errors,clearErrors, reset,assignPlan,setAssignPlan,isConfirm,setIsConfirm,duplicatedCheck,setDuplicatedCheck,selectedDate,setSelectedDate,choicedMap,setChoicedMap,staffInChoice,setStaffInChoice,popUpVisible,setPopUpVisible,pageMinWidth,pageMaxWidth}
}
