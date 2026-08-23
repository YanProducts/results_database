import React from "react";
import { useForm } from "@inertiajs/react";

// 報告書編集の定義(fieldStaffと重なる部分も多いが、違う部分もあるので、ひとまず別途定義)
export default function useEditReportDefinitions({staff,dateSet}){

 // Y-m-dの日付(dateSetsより。何度も使うので前もって呼び出す)
  const date=Object.keys(dateSet)[0];

  // フォーム
    const { data, setData, post, processing, errors,clearErrors, reset}=useForm({
        // staffIdはLaravel側のauth::user()で操作
        "date":date,
        "staff":staff,
        "changedReportData":[]
    });

     //  報告書入力か確認か
    const [isConfirm,setIsConfirm]=React.useState(false);

    //   持ち出し部数(表示のみ)
    const [issuedCount,setIssuedCount]=React.useState(0)

    //   返却部数(表示のみ)
    const [returnedCount,setReturnedCount]=React.useState(0)

     // inputのvalue
     const [inputValues, setInputValues]=React.useState({});

     //  inputのRef
    const inputRefs=React.useRef([]);

    // 何かしらの変化が生じた部位(Tailwindで枠線を色を変え太くする)
    const [changedData,setChangedData]=React.useState([]);

      // ページの横幅
    const [pageMinWidth,pageMaxWidth]=["min-w-90 mobile:min-w-250","max-w-300 mobile:max-w-400"];

    // モバイルが大きい時と小さい時で表示を変える
    const [isBigMedia,setIsBigMedia]=React.useState(window.matchMedia("(min-width:500px)").matches);

      return {data, setData, post, processing, errors,clearErrors, reset,isConfirm,setIsConfirm,issuedCount,setIssuedCount,returnedCount,setReturnedCount,inputValues,setInputValues,inputRefs,changedData,setChangedData,pageMinWidth,pageMaxWidth,isBigMedia,setIsBigMedia,date};
}
