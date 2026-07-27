import { useForm } from "@inertiajs/react";
import React from "react";
import { formatDateForServer, formatYearAndMonthForView } from "../../Support/Common/formatDateForView";

export default function usePurchaseOrderDefinitions({defaultStartDateForPurchaseLists}){

      // フォーム(errorsのみ手動挿入で使用)
      const { data, setData, post, processing, errors,clearErrors, reset}=useForm();


    // 取得できるのは何ヶ月前からか(ユーザーが変更可能) //初期は-36
    const [limitMonth,setLimitMonth]=React.useState(defaultStartDateForPurchaseLists)

    // 取得できる範囲は何年前からか(1~10年前)=1~10の配列
    const limitYearLists=Object.fromEntries(Array.from({length:10},(_,i)=>i+1).map(eachNumber=>([[eachNumber],eachNumber + "年前"])))

    //  選択中のスタッフ
    const [selectedStaff,setSelectedStaff]=React.useState("");

    // 選択中の開始年月(Date型:別途サーバー提出と表示用にセット)
    const [selectedStartMonth,setSelectedStartMonth]=React.useState();

    // 選択中の終了年月(Date型:別途サーバー提出と表示用にセット)
    const [selectedEndMonth,setSelectedEndMonth]=React.useState();

    // 非同期送信中にロジックを止める(UIが反映される前に二重投稿防止)
    const processingRef = React.useRef(false);

    // 非同期通信中にボタンが押せるかどうか(UI)
    const [buttonOk,setButtonOk]=React.useState(true);

    // ページの横幅
      const [pageMinWidth,pageMaxWidth]=["min-w-120","max-w-300"];

      return {errors,clearErrors,limitMonth,setLimitMonth,limitYearLists,selectedStaff,setSelectedStaff,selectedStartMonth,setSelectedStartMonth,selectedEndMonth,setSelectedEndMonth,processingRef,buttonOk,setButtonOk,pageMinWidth,pageMaxWidth};
}
