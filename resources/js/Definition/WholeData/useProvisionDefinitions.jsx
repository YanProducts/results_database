import { useForm } from "@inertiajs/react";

export default function useProvisionDefinitions(){


  // フォーム
  const { data, setData, post, processing, errors, reset}=useForm({
    role:"",
    userName:"",
    // 以下は場合によって空白になる場合あり
    place:"",
    staffName:""
  });

  return { data, setData, post, processing, errors, reset}
}
