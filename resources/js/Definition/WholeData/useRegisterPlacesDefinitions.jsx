import { useForm } from "@inertiajs/react";

export default function useRegisterPlacesDefinitions(){

  // フォーム
  const { data, setData, post, processing, errors, reset}=useForm({
    // 以下は場合によって空白になる場合あり
    place:"",
    // RGBのセット
    colors:{
        "red":255,
        "green":255,
        "blue":255,
    }
  });

  return { data, setData, post, processing, errors, reset}
}
