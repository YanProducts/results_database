import { useForm } from "@inertiajs/react";
import React from "react";
import { formatDateForServer, formatYearAndMonthForView } from "../../Support/Common/formatDateForView";

export default function usePurchaseOrderDefinitions({defaultStartDateForPurchaseLists}){

    // フォーム
    const { data, setData, post, processing, errors,clearErrors, reset}=useForm({});

    // 取得できるのは何ヶ月前からか(ユーザーが変更可能=変更したらaxiosで取得) //初期は-36
    const [limitMonth,setLimitMonth]=React.useState(defaultStartDateForPurchaseLists)

    // 取得できる範囲は何年前からか(1~10年前)=1~10の配列
    const limitYearLists=Object.fromEntries(Array.from({length:10},(_,i)=>i+1).map(eachNumber=>([[eachNumber],eachNumber + "年前"])))

    //  選択中のスタッフ
    const [selectedStaff,setSelectedStaff]=React.useState("");

    // 選択中の開始年月(Date型:別途サーバー提出と表示用にセット)
    const [selectedStartMonth,setSelectedStartMonth]=React.useState(new Date());

    // 選択中の終了年月(Date型:別途サーバー提出と表示用にセット)
    const [selectedEndMonth,setSelectedEndMonth]=React.useState(new Date());

    // ページの横幅
      const [pageMinWidth,pageMaxWidth]=["min-w-120","max-w-300"];

      return {data, setData, post, processing, errors,clearErrors,reset,limitMonth,setLimitMonth,limitYearLists,selectedStaff,setSelectedStaff,selectedStartMonth,setSelectedStartMonth,selectedEndMonth,setSelectedEndMonth,pageMinWidth,pageMaxWidth};
}
