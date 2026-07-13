import { route } from "ziggy-js";
import axios from "axios";

// 案件確認のcsvを作成-json受け取り-ダウンロード
// asyncはuseEffectでは定義できない(Promiceを返す処理になるため)ので、内部定義
export const createAndDownloadCSV=async(data,setData)=>{
        // 何もされていなければ何もしない
        if(Object.keys(data).length==0){
            return;
        }

        try{
            const response = await axios.post(route("clerical.create_report_csv"),{idSets: data.idSets});
            if (!response.data.is_create) {
                alert("CSV作成に失敗しました");
                return;
            }
            setData({});
            window.location=route("clerical.download_report_csv");
        }catch(e){
            alert("ファイル作成中にエラーが生じました");
            return;
        }

     }



