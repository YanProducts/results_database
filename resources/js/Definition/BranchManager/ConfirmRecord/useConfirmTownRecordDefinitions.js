import React from "react";
import { useForm } from "@inertiajs/react";

// 過去の町々目データの確認(町ごと)
export default function useConfirmTownRecordDefinitions({startOffset}){

     // フォーム
      const { data, setData, post, processing, errors,clearErrors, reset}=useForm({
        // staffは「全体、その営業所全体」はどの場合も出力するため選択項目から除外 //限定しやスタッフの平均などを出したい時のために出力
        // 町々目は1：兵庫と大阪の都市から選択、2：データを貼り付け
      });

      // 取得するスタッフ
      const [selectedStaffs,setSelectedStaffs]=React.useState([])

      // 期限のリスト(10年前)
      const dateLists=Object.fromEntries(Array.from({length:startOffset},(_,i)=>i).map(eachNumber=>([eachNumber+1,eachNumber+1 + "年前"])));

      // 期限の選択
      const [selectedStartYear,setSelectedStartYear]=React.useState(1);
      const [selectedEndYear,setSelectedEndYear]=React.useState(-1);


      // townはどちらから取得するか
      const [townChoiceMode,setTownChoiceMode]=React.useState("select");

     // townを選択する場合
     // ~県...市まで選択。その後は「全域」もしくは「町丁目」
     const [prefBySelect,setPrefBySelect]=React.useState("");
     const [cityBySelect,setCityBySelect]=React.useState("");
     const [townBySelect,setTownBySelect]=React.useState("");

     //  townを貼り付けセットから取得する場合
     const [townDataByList,setTownDataByList]=React.useState();

    // ページの横幅
    const [pageMinWidth,pageMaxWidth]=["min-w-120","max-w-200"];

    return  {data, setData, post, processing, errors,clearErrors, reset,selectedStaffs,setSelectedStaffs,dateLists,selectedStartYear,setSelectedStartYear,selectedEndYear,setSelectedEndYear,townChoiceMode,setTownChoiceMode,prefBySelect,setPrefBySelect,cityBySelect,setCityBySelect,townBySelect,setTownBySelect,townDataByList,setTownDataByList,pageMinWidth,pageMaxWidth};
}
