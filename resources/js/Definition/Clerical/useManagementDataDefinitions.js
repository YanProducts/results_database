import { useForm } from "@inertiajs/react";
import React from "react";

export default function useManagementDataDefinitions(){

      // フォーム
      const { data, setData, post, processing, errors,clearErrors, reset}=useForm({});

     // CSVにエクスポートするかのチェック一覧
      const [CSVOutputSets,setCSVOutputSets]=React.useState({});

    // 案件完成フラグ
      const [isComplete,setIsComplete]=React.useState({});

    // ページの横幅
      const [pageMinWidth,pageMaxWidth]=["min-w-200","max-w-300"];

      return {data, setData, post, processing, errors,clearErrors, reset,CSVOutputSets,setCSVOutputSets,isComplete,setIsComplete,pageMinWidth,pageMaxWidth};
}
