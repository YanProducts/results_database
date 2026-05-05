import { useForm } from "@inertiajs/react";
import React from "react";

export default function useWriteReportDefinitions(){
      // フォーム
      const { data, setData, post, processing, errors,clearErrors, reset}=useForm({
            // staffIdはLaravel側のauth::user()で操作
            "date":"",
            "reportData":[]
      });

     //  報告書入力か確認か
     const [isConfirm,setIsConfirm]=React.useState(false);

      // 表示する日付(選択中のY-m-d型の文字列を返す)
      const [selectedDate,setSelectedDate]=React.useState("");

     // inputのvalue
     const [inputValues, setInputValues]=React.useState([]);

     //  inputのRef
      const inputRefs=React.useRef([]);

    // ページの横幅
      const [pageMinWidth,pageMaxWidth]=["min-w-200","max-w-300"];

      return {data, setData, post, processing, errors,clearErrors, reset,isConfirm,setIsConfirm,selectedDate,setSelectedDate,inputValues,setInputValues,inputRefs,pageMinWidth,pageMaxWidth};
}
