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

    //   持ち出し部数(表示のみ)
    const [issuedCount,setIssuedCount]=React.useState(0)

    //   返却部数(表示のみ)
    const [returnedCount,setReturnedCount]=React.useState(0)

     // inputのvalue
     const [inputValues, setInputValues]=React.useState({});

     //  inputのRef
      const inputRefs=React.useRef([]);

      // ページの横幅
    const [pageMinWidth,pageMaxWidth]=["min-w-100 mobile:min-w-250","max-w-300 mobile:max-w-400"];

      return {data, setData, post, processing, errors,clearErrors, reset,isConfirm,setIsConfirm,selectedDate,setSelectedDate,issuedCount,setIssuedCount,returnedCount,setReturnedCount,inputValues,setInputValues,inputRefs,pageMinWidth,pageMaxWidth};
}
