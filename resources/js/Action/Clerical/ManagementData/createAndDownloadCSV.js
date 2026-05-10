import { route } from "ziggy-js";

// 案件確認のcsvを作成-json受け取り-ダウンロード
// asyncはuseEffectでは定義できない(Promiceを返す処理になるため)ので、内部定義
export const createAndDownloadCSV=async(data)=>{
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
            setData({});
            window.location=route("clerical.download_report_csv");
        }
    }



