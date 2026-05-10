import { route } from "ziggy-js";
import React from "react";

export default function useManagementDataActions({post,data,setData,CSVOutputSets,setCSVOutputSets,isComplete,setIsComplete,projectsInSql}){

    // 確認用
    React.useEffect(()=>{
        console.log(CSVOutputSets)
    },[CSVOutputSets])


    React.useEffect(()=>{
        // CSVエクスポートするかの初期状態
        // キーに案件名、valueに{id:とisExport:}が入ったオブジェクト
        const outPutSets=Object.fromEntries(Object.entries(projectsInSql).map((projectKeyValues)=>([projectKeyValues[1].project_name,{"id":projectKeyValues[0],"isExport":false}])))
        setCSVOutputSets({outPutSets})

        // 案件の入力が完成しているかのフラグ
        // キーに案件名、valueに{id:とcompleteFlag:}が入ったオブジェクト
        const completeSets=Object.fromEntries(Object.entries(projectsInSql).map((projectKeyValues)=>[projectKeyValues[1].project_name,{"id":projectKeyValues[0],"completeFlag":projectKeyValues[1].is_complete}]));
        setIsComplete(completeSets);
    },[])

    // データがセットされたらCSVエクスポート(ボタンを押したらデータが変換される)
    React.useEffect(async()=>{

        // 何もされていなければ何もしない
        if(Object.keys(data).length==0){
            return;
        }

        // fetchでdataを投稿してjsonを返還
        const response=await fetch(
            route("clerical.create_report_csv"),{
                "idSets":data.idSets
        })

        // ファイル作成エラーのとき
        try{
            if(!response.ok){
                throw new Error()
            }
            const json=response.json()
            if(!json.isCreate){
                throw new Error()
            }
        }catch(e){
            alert("ファイル作成中にエラーが生じました");
            return;
        }

        // レスポンスが返ってきたらダウンロード処理を通常のgetで行う(inertiaはファイルレスポンスと相性悪い)
        if(json.createOk){
            window.location=route("clerical.download_report_csv");
        }


    },[data])



    // CSVエクスポートのチェックボックスが変化した時
    const onExportCheckChange=(e,projectName,projectId)=>{
        setCSVOutputSets(prev=>({...prev,
            projectName:{
                "id":"projectId",
                "isExport":e.currentTarget.checked
            }}));
    }

    // 完成フラグのチェンジ(決定ボタンで投稿)
    const onCompleteCheckClick=async(e,projectName,projectId)=>{
        // fetchで取得する
        const response= await fetch(route("clerical.toggle_complete"),{
            "body":{
                // 同案件フラグもあるのでidも出す
                "projectName":projectName,
                "projectId":projectId
            }
        });
        if(!response.ok){
            alert("処理に失敗しました\n再度読み込んで失敗が続く場合は作成者に連絡してください")
            return;
        }
        const responseData=await response.json()

        if(responseData.fetchError || !responseData?.isOk){
            if(responseData.fetchError?.projectChange){
                alert("途中で案件データが操作されているので、１度リロードします");
                location.reload();
                return;
            }
            alert("予期せぬエラーです\n再度読み込んで失敗が続く場合は作成者に連絡してください");
            return;
        }

        // エラーがなければ、完成フラグのみ反転
        setIsComplete(prev=>({...prev,
            [projectName]:{
                id:projectId,
                completeFlag:!prev[projectName].completeFlag
        }}));

    }

    // 報告書CSVに実際にエクスポート
    const onDecideExportData=(e)=>{
        e.preventDefault();

        // ここで"projectIds":[,,,]の配列に直す
        const changeIds=Object.values(CSVOutputSets).map(eachSets=>eachSets.id)


        //projectName:{id...,isExport...}の形式で保存し、useEffectで投稿
        setData({"idSets":changeIds});
    }

    return {onExportCheckChange,onCompleteCheckClick,onDecideExportData}
}
