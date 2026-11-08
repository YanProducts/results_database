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

    // 案件のみからきた場合の表示用の地図番号選択(案件:地図番号の配列)
    const [choicedMap,setChoicedMap]=React.useState({})

    // 案件のみからきた場合の表示用の地図選択が一時的に完了している案件
    const [choiceFinishedMapProjectLists,setChoiceFinishedMapProjectLists]=React.useState([]);

      // ページの横幅
    const [pageMinWidth,pageMaxWidth]=["min_width_when_write_report","max_width_when_write_report"];

    // モバイルが大きい時と小さい時で表示を買える
    const [isBigMedia,setIsBigMedia]=React.useState(window.matchMedia("(min-width:500px)").matches);

      return {data, setData, post, processing, errors,clearErrors, reset,isConfirm,setIsConfirm,selectedDate,setSelectedDate,issuedCount,setIssuedCount,returnedCount,setReturnedCount,inputValues,setInputValues,inputRefs,choicedMap,setChoicedMap,choiceFinishedMapProjectLists,setChoiceFinishedMapProjectLists,pageMinWidth,pageMaxWidth,isBigMedia,setIsBigMedia};
}
