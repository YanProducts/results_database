import { useForm } from "@inertiajs/react";
import React from "react";

export default function useAssignProjectToStaffDefinitions(){

  // フォーム
  const { data, setData, post, processing, errors, reset}=useForm({
        // 送信はバリデーションのしやすさも含め、[staff_id=>[plan_idの配列]]という配列
  });

  // フォームに送信する前段階では、planIdをキーにし、スタッフIdを配列にして保存(変更がしやすいため)
  const [assignPlan,setAssignPlan]=React.useState({});

  // 表示する日付(選択中のY-m-d型の文字列を返す)
  const [selectedDate,setSelectedDate]=React.useState();
  // 表示するメイン案件名
  const [selectedMainProject,setSelectedMainProject]=React.useState();


  // 割り当てはMapNumberと町目直接のどちらで行うか
  const [needNumber,setNeedNumber]=React.useState("mapNumber");

 // mapにスタッフを割り当てた場合の表示用のstate。mainProject:{mapNumber:スタッフ名}の形式
  const [selectedMapNumber,setSelectedMapNumber]=React.useState({});

// 町目にスタッフを割り当てたとき
 // main案件のプロジェクト名前:{townId:"" Staff:""}のオブジェクト
const [townAssign,setTownAssign]=React.useState({

});



  return { data, setData, post, processing, errors, reset,assignPlan,setAssignPlan,selectedDate,setSelectedDate,selectedMainProject,setSelectedMainProject,needNumber,setNeedNumber,selectedMapNumber,setSelectedMapNumber,townAssign,setTownAssign}
}
