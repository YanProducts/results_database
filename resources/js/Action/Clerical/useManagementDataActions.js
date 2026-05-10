import { route } from "ziggy-js";
import React from "react";
import { createAndDownloadCSV } from "./ManagementData/createAndDownloadCSV";
import { useIsCompleteCheckClick } from "./ManagementData/useIsCompleteCheckClick";

export default function useManagementDataActions({post,data,setData,CSVOutputSets,setCSVOutputSets,isComplete,setIsComplete,projectsInSql}){

    // 確認用
    React.useEffect(()=>{
        // console.log(CSVOutputSets)
    },[CSVOutputSets])


    React.useEffect(()=>{
        // CSVエクスポートするかの初期状態
        // キーに案件名、valueに{id:とisExport:}が入ったオブジェクト
        const outPutSets=Object.fromEntries(Object.entries(projectsInSql).map((projectKeyValues)=>([projectKeyValues[1].project_name,{"id":projectKeyValues[0],"isExport":false}])))
        setCSVOutputSets(outPutSets)

        // 案件の入力が完成しているかのフラグ
        // キーに案件名、valueに{id:とcompleteFlag:}が入ったオブジェクト
        const completeSets=Object.fromEntries(Object.entries(projectsInSql).map((projectKeyValues)=>[projectKeyValues[1].project_name,{"id":projectKeyValues[0],"completeFlag":projectKeyValues[1].is_complete}]));
        setIsComplete(completeSets);
    },[])

    // データがセットされたらCSVエクスポート(ボタンを押したらデータが変換される)
    React.useEffect(()=>{
        // 案件確認のcsvを作成-json受け取り-ダウンロード
        createAndDownloadCSV(data);
    },[data])



    // CSVエクスポートのチェックボックスが変化した時
    const onExportCheckChange=(e,projectName,projectId)=>{
        setCSVOutputSets(prev=>({...prev,
            [projectName]:{
                "id":projectId,
                "isExport":!prev[projectName].isExport
            }}));
    }

    // 完成フラグのチェンジ(決定ボタンで投稿)
    const onCompleteCheckClick=(e,projectName,projectId)=>{useIsCompleteCheckClick(e,projectName,projectId,setIsComplete)}


    // 報告書CSVエクスポートがクリック=データ変換=dataに挿入=>useEffect作動
    const onDecideExportData=(e)=>{
        e.preventDefault();

        // ここで"projectIds":[,,,]の配列に直す
        const changeIds=Object.values(CSVOutputSets).map(eachSets=>eachSets.id)

        //projectName:{id...,isExport...}の形式で保存し、useEffectで投稿
        setData({"idSets":changeIds});
    }

    return {onExportCheckChange,onCompleteCheckClick,onDecideExportData}
}
