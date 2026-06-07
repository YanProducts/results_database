import { useForm } from "@inertiajs/react";
import React from "react";

// 案件確認の定義
export default function useProjectOverviewDefinitions(){

    const {data, setData, post, processing, errors,clearErrors, reset}=useForm({})

    // ページの横幅
    const [pageMinWidth,pageMaxWidth]=["min-w-250","max-w-350"];

    // 最優先sortは何か？
    const [prioritySort,setPrioritySort]=React.useState("project_id");

    // hiddenにさせるかどうかの表で見せる内容は何か
    const [columnForHiddenLists,setColumnForHiddenLists]=React.useState("");

    // hiddenさせるパターン
    const [hiddenPattern,setHiddenPattern]=React.useState({
        "案件名":["valueを撮ってこよう"],
        "開始日":["~3日より前","2日前","1日前","今日","明日","2日後より後"],
        "終了日":["~3日より前","2日前","1日前","今日","明日","2日後より後"],
        "割当済町目数":["~5","6~10","11~20","25~50","51~100","100~"],
        "配布済町目数":["~5","6~10","11~20","25~50","51~100","100~"],
        "設定部数":["~3000","3001~10000","10001~20000","20001~50000","50001~100000","100001~"],
        "現在配布部数":["~1000","1001~3000","3001~10000","10001~20000","20001~50000","50001~100000","100001~"],
    });


    // hiddenさせる内容のリスト(値そのままか、何か以上以下か)
    const [allHiddenLists,setAllHiddenLists]=React.useState({
        "案件名":{},
        "開始日":{},
        "終了日":{},
        "割当済町目数":{},
        "配布済町目数":{},
        "設定部数":{},
        "現在配布部数":{},
    });

      return {data, setData, post, processing, errors,clearErrors, reset,columnForHiddenLists,setColumnForHiddenLists,prioritySort,setPrioritySort,allHiddenLists,setAllHiddenLists,pageMinWidth,pageMaxWidth};
}
