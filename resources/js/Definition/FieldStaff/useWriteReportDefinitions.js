import React from "react";
import useReportCommonDefinitions from "../Share/useReportCommonDefinitions";

export default function useWriteReportDefinitions(){

    // 営業所担当との共通部分
    const commonDefinitions=useReportCommonDefinitions({defaultData:{"date":"","reportData":[]},defaultInputValues:{}});


    // 表示する日付(選択中のY-m-d型の文字列を返す)
    const [selectedDate,setSelectedDate]=React.useState("");


    return {...commonDefinitions,selectedDate,setSelectedDate};
}
