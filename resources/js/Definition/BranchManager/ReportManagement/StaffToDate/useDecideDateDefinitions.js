import React from "react";
import { useForm } from "@inertiajs/react";

// スタッフ→日付の決定
export default function useDecideDateDefinitions(){

    // フォーム
    const { data, setData, post, processing, errors,clearErrors, reset}=useForm({});

    // ページの横幅
    const [pageMinWidth,pageMaxWidth]=["min-w-200","max-w-300"];

    return {data,setData,post,processing, errors,clearErrors, reset, pageMinWidth,pageMaxWidth}
}
