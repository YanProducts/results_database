import { useForm } from "@inertiajs/react";
import React from "react";

export default function useWriteReportDefinitions(){
      // フォーム
      const { data, setData, post, processing, errors,clearErrors, reset}=useForm({
            // staffIdはLaravel側のauth::user()で操作
            "date":"",
            "reportData":[]
      });

      // 表示する日付(選択中のY-m-d型の文字列を返す)
      const [selectedDate,setSelectedDate]=React.useState("");

    // ページの横幅
      const [pageMinWidth,pageMaxWidth]=["min-w-200","max-w-300"];

      return {data, setData, post, processing, errors,clearErrors, reset,selectedDate,setSelectedDate,pageMinWidth,pageMaxWidth};
}
