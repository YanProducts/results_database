import React from "react";
import useReportCommonDefinitions from "../../Share/useReportCommonDefinitions";
import getDefaultValueForEditReport from "../../../Support/Share/getDefaultValueForEditReport";

// 報告書編集の定義(fieldStaffと重なる部分も多いが、違う部分もあるので、ひとまず別途定義)
export default function useEditReportDefinitions({assignWithRecords,staff,dateSet,projectOnlyFlag}){

        // Y-m-dの日付(dateSetsより。何度も使うので前もって呼び出す)
        //dateは後にも使うので先に定義
        const date=Object.keys(dateSet)[0];

        // 現場担当との共通部分
        const commonDefinitions=useReportCommonDefinitions({defaultData:{ "date":date,"staff":staff,"projectOnlyFlag":projectOnlyFlag,"changedReportData":[]},defaultInputValues:getDefaultValueForEditReport({assignWithRecords,date,projectOnlyFlag})});

        // 何かしらの変化が生じた部位(Tailwindで枠線を色を変え太くする)
        const [changedData,setChangedData]=React.useState({});

        return {...commonDefinitions,date,changedData,setChangedData};
}
