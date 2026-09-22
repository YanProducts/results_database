import React from "react";
import { useForm } from "@inertiajs/react";
import putDefaultValueInReportData from "../../Action/Clerical/WriteReport/putDefaultValueInReportData";

export default function useWriteReportDefinitions({projectId,reportDataInTheProject}){

     // フォーム
     const { data, setData, post, processing, errors,clearErrors, reset}=useForm({
        "projectId":projectId,
        // 編集したplanIdのみ変化して、元のデータとの差分が格納される
        "changedDifference":[]
     });


    // 現在入力されている値
    // スタッフが分割して行っている場合は、それごとの値が必要
    // 日付やスタッフごとの編集は営業所担当が行い(町目自体の配布データを崩さないため)、入力担当は合計のみ変化させisUnknownをtrueで格納
     const [dataInReport,setDataInReport]=React.useState(
        putDefaultValueInReportData({reportDataInTheProject})
     );

    //  変化したplanId(表示&最終投稿計算時にここからmap)...色を変える
    const [changedId,setChangedId]=React.useState([]);

    // 注意書きの消去
    const [firstAttention,setFirstAttention]=React.useState(true);

    // バリデーションを表示させるか
    const [validationHidden,setValidationHidden]=React.useState(false); //初期は表示させる(hiddenにさせない)

     //  確認か否か
     const [isConfirm,setIsConfirm]=React.useState(false);

     // ページの横幅
      const [pageMinWidth,pageMaxWidth]=["min-w-250","max-w-350"];

      return {data, setData, post, processing, errors,clearErrors, reset,dataInReport,setDataInReport,changedId,setChangedId,firstAttention,setFirstAttention,validationHidden,setValidationHidden,isConfirm,setIsConfirm,pageMinWidth,pageMaxWidth}
}
