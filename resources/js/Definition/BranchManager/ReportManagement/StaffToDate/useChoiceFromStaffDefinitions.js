import React from "react";
import { useForm } from "@inertiajs/react";

// 報告書の確認をスタッフから行う場合の定義
export default function useChoiceFromStaffDefinitions(){

    // フォーム
    const { data, setData, post, processing, errors,clearErrors, reset}=useForm();

    // 選択中のスタッフ
    const [selectedStaffs,setSelectedStaffs]=React.useState([]);

    // ページの横幅
    const [pageMinWidth,pageMaxWidth]=["min-w-200","max-w-300"];

    return {data,setData,post,processing, errors,clearErrors, reset, selectedStaffs,setSelectedStaffs,pageMinWidth,pageMaxWidth}
}
