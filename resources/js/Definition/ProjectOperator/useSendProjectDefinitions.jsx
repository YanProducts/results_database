import { useForm } from "@inertiajs/react";
import React from "react";

export default function useSendProjectDefinitions(){

  // フォーム
  const { data, setData, post, processing, errors, reset}=useForm({

  });

  React.useEffect(()=>{

  },[])

  return { data, setData, post, processing, errors, reset}
}
