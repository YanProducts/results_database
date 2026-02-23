import { useForm } from "@inertiajs/react";
import React from "react";

export default function useProvisionDefinitions(nonPlaceAlert){


  // フォーム
  const { data, setData, post, processing, errors, reset}=useForm({
    role:"",
    userName:"",
    // 以下は場合によって空白になる場合あり
    place:"",
    staffName:""
  });

  React.useEffect(()=>{
    // 営業所の事前登録が必要なアラート
    if(nonPlaceAlert){
        alert("現場担当と営業所担当の登録には\n事前に営業所登録が必要です")
    }
    // 最初のレンダリング終了のみ発火でも同じだが、依存している値はnonPlaceAlertのため、これを入れる
  },[nonPlaceAlert])

  return { data, setData, post, processing, errors, reset}
}
