// Computed層はstateから得られた変数を表示用データに返還する層
// その過程で必要とする変数が変更されなければ変更しないので、useMemoを使用することが多い

import React from "react";
import getTableSetsByMainProjects from "../../Support/FieldStaff/getTableSetsByMainProjetcs";
// このファイルはスタッフの報告書作成における表示データの取得
export default function useWriteReportViewData({assignDataToStaff,selectedDate,inputValues,issuedCount,returnedCount,isBigMedia}){


       const tableSets=React.useMemo(()=>{
        if(!selectedDate){
            return [];
        }

        return getTableSetsByMainProjects({assignDataToStaff,selectedDate,inputValues,issuedCount,returnedCount,isBigMedia})},
        [assignDataToStaff,selectedDate,inputValues,issuedCount,returnedCount,isBigMedia]);

        // オブジェクトが入れ子になっている配列の中のsumSet(合計セット)に「持ち出し-返却」と「町目ごとの数の合計数」が全てあっているかの確認(間違いがないかの確認)
        // 1つでもずれがあればtrueを返す
        const differenceExisits= React.useMemo(()=>{
            // そもそもdateやtableがセットされてない場合
            if(!selectedDate || !tableSets || tableSets.length==0){
                return false;
            }

            return (tableSets.some(function(eachDataByMainProjects){
                    return(
                        Object.values(eachDataByMainProjects?.sumSets).some((eachSubSet)=>eachSubSet?.difference!=0)
            )}))},[assignDataToStaff,selectedDate,inputValues,issuedCount,returnedCount,tableSets])

        return [tableSets,differenceExisits];
}
