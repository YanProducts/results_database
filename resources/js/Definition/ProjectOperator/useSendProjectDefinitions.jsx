import { useForm } from "@inertiajs/react";
import React from "react";

export default function useSendProjectDefinitions(){

  // フォーム
  const { data, setData, post, processing, errors, reset}=useForm({
    // 案件期日
    "startDate":{},
    "endDate":{},
    // 営業所名
    "place":{},
    // 案件セット
    "fileSets":[]
  });


  return { data, setData, post, processing, errors, reset}
}
