import { route } from "ziggy-js";

// 案件確認のcsvを作成-json受け取り-ダウンロード
// asyncはuseEffectでは定義できない(Promiceを返す処理になるため)ので、内部定義
export const createAndDownloadCSV=async(data,setData)=>{
        // 何もされていなければ何もしない
        if(Object.keys(data).length==0){
            return;
        }

        try{

         // fetchでdataを投稿してjsonを返還
          const response=await fetch(
            route("clerical.create_report_csv"),{
                method:"POST",
                credentials:"same-origin",
                headers:{
                "Content-Type":"application/json",
                "X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').content,
                },
                body:JSON.stringify({"idSets":data.idSets})
             }
            )

            if(!response.ok){
                console.log(document.querySelector('meta[name="csrf-token"]').content)
                console.log(response);
                alert("通信エラーが発生しました")
                return;
            }
            const json=await response.json()
            if(!json.is_create){
                alert("CSV作成に失敗しました")
                return;
            }
            setData({});
            window.location=route("clerical.download_report_csv");
        }catch(e){
            alert("ファイル作成中にエラーが生じました");
            return;
        }

     }



