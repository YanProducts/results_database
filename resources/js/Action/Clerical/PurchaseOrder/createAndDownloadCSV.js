import { route } from "ziggy-js";
import axios from "axios";
import handleAxiosError from "../../../Support/Common/handleAxiosError";

// 発注書のCSV作成とダウンロード
export default async function createAndDownloadCSV({e,processingRef,setButtonOk,selectedStaff,selectedStartMonth,selectedEndMonth}){
        e.preventDefault();

        // ロジック内部での二重投稿の制御(axiosはinertiaではないのでprocessingは使えない)
        if (processingRef.current) return;

        // 未選択の項目
        if(!selectedStaff || !selectedStartMonth || !selectedEndMonth){
            alert("選択できていない項目があります")
            return;
        }

        // 二重投稿防止のフラグ(axiosはinertiaではないのでprocessingは使えない)
        processingRef.current = true;


        // レスポンス終了までボタンを押せないようにする(Inertiaではないのでprocessingは反映されない) (またRefだけだとUIを動的に変更しない)
        setButtonOk(false)

        // 非同期で送る
        try{
            // axiosで非同期でまずはCSV作成
            const response=await axios.post(route("clerical.create_purchase_order_csv"),{
                "staffId":selectedStaff,
                "startMonth":selectedStartMonth,
                "endMonth":selectedEndMonth
            })
            // 返却されていないとき
            if(!response.data.ExportFlowResult){
                throw new Error("サーバー処理時のエラーです")
            }
            // Okではない時
            if(response.data.ExportFlowResult!="Ok"){
                throw new Error(response.data.ExportFlowResult)
            }

            // 作成までが無事完了していたらダウンロード
            // ここでerrorが起きていたらInertiaのerrorsに格納
            window.location=route("clerical.download_purchase_order");

        }catch(e){
            // axiosのエラー処理
            handleAxiosError(e)
        }finally{
            // ロジックを可能にする(axiosはinertiaではないのでprocessingは使えない)
            processingRef.current=false;
            // UIを動かせるようにする
            setButtonOk(true)
        }
}
