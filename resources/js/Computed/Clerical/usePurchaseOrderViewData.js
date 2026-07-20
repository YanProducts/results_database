import React from "react";
import { formatYearAndMonthForView } from "../../Support/Common/formatDateForView";

// 発注書作成における表示用のデータ
// 月リストの取得(Y-m-d(=dはserver用)をキーに、表示を日本語にしたオブジェクト)
// 取得限度月の変化に応じて変更
export default function usePurchaseOrderViewData({limitMonth}){

    const monthSets=React.useMemo(()=>{
        let monthSetsInMemo={};
        if(!limitMonth || isNaN(limitMonth) || limitMonth<0){
            return {};
        }

        // まずは現在のDateを取得し、そこから取得限度月のDate型の取得
        const monthByDateForm=new Date();
        monthByDateForm.setMonth((monthByDateForm.getMonth())-limitMonth)

        do{
            // 月日のオブジェクトに追加
            monthSetsInMemo[monthByDateForm]=formatYearAndMonthForView(monthByDateForm);
            // 1か月たす
            monthByDateForm.setMonth(monthByDateForm.getMonth()+1)
        }while(monthByDateForm<new Date())
        return monthSetsInMemo;
    },[limitMonth]);
    return monthSets
}
