import { useForm } from "@inertiajs/react";
import React from "react";
import baseHiddenPatterns from "../../Support/ProjectOperator/hiddenPattern";

// 案件確認の定義
export default function useProjectOverviewDefinitions(){

    const {data, setData, post, processing, errors,clearErrors, reset}=useForm({})

    // ページの横幅
    const [pageMinWidth,pageMaxWidth]=["min-w-250","max-w-350"];

    // 最優先sortは何か？
    const [prioritySort,setPrioritySort]=React.useState("project_id");

    // hiddenにさせるかどうかの表で見せる内容は何か
    const [columnForHiddenLists,setColumnForHiddenLists]=React.useState("");

    const [hiddenListVisible,setHiddenListsVisible]=React.useState(false)

    // hiddenさせるパターン
    const hiddenPatterns=baseHiddenPatterns();

    // 実際にhiddenさせる内容のリスト(hiddenパターンのクリックから外れたもののリスト)
    const [allHiddenLists,setAllHiddenLists]=React.useState({
        "案件名":[],
        "開始日":[],
        "終了日":[],
        "割当済町目数":[],
        "配布済町目数":[],
        "設定部数":[],
        "現在配布部数":[],
    });

      return {data, setData, post, processing, errors,clearErrors, reset,prioritySort,setPrioritySort,columnForHiddenLists,setColumnForHiddenLists,hiddenListVisible,setHiddenListsVisible,hiddenPatterns,allHiddenLists,setAllHiddenLists,pageMinWidth,pageMaxWidth};
}
