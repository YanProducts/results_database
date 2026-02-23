import { useForm } from "@inertiajs/react";
import React from "react";

export default function useAdminOverviewDefinitions(){

  // フォーム
  const { data, setData, post, processing, errors, reset}=useForm({
    // ユーザーの場合
      role:"",
      userId:"",
    // 営業所の場合
      placeId:"",
  });


  return { data, setData, post, processing, errors, reset}
}
