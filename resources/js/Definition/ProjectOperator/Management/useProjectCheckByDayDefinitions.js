import { useForm } from "@inertiajs/react";
import projectDataFlatter from "../../../Support/ProjectOperator/projectDataFlatter";
import React from "react";


// 案件確認の定義
export default function useProjectCheckByDayDefinitions({projectData}){

    const {data, setData, post, processing, errors,clearErrors, reset}=useForm({})

    // リストで表示する内容
    const checkByDayItems={
        "start_date":"開始日",
        "place_name":"営業所",
        "main_project_name":"案件名",
        "round_number":"回数",
        "sub_project_lists":"併配",
        "city_name_lists":"エリア",
        "end_date":"終了日",
    }

    // テーブルの長さ
    const thWidthSets=[
        "w-[10%]",
        "w-[10%]",
        "w-[10%]",
        "w-[5%]",
        "w-[25%]",
        "w-[25%]",
        "w-[10%]",
        "w-[5%]"
    ];

        // 平坦化したデータ
    const flattedData=projectDataFlatter(projectData);

    // // 現在radioで選択中のソート項目
    // const [selectedSort,setSelectedSort]=React.useState("project_id");

    // // 決定した最優先sortは何か？
    // const [prioritySort,setPrioritySort]=React.useState("project_id");

    // // 現在radioで設定中の昇順降順
    // const [selectedAscOrDes,setSelectedAscOrDes]=React.useState("asc");

    // //決定した昇順降順のどちらか
    // const [ascOrDes,setAscOrDes]=React.useState("asc");

    // // sort変更のリストを表示するか？
    // const [sortItemIsVisible,setSortItemIsVisible]=React.useState(false);


    // // hiddenにさせるかどうかの表で見せる内容は何か
    // const [columnForHiddenLists,setColumnForHiddenLists]=React.useState("");

    // // popUpを表示させるか(true/falseでtailwind変化)
    // const [hiddenListVisible,setHiddenListsVisible]=React.useState(false)

    // // hiddenさせるリストの選択肢
    // const hiddenPatterns=baseHiddenPatterns();

    // // 実際にhiddenさせる内容のリスト(hiddenパターンのクリックから外れたもののリスト)
    // const [allHiddenLists,setAllHiddenLists]=React.useState(Object.fromEntries(Object.values(overViewItems).map(eachItem=>[eachItem,[]])));

    // // そのカラムに「全表示」ボタンがチェックされているか(チェックが入っているカラムを捕捉)
    // const[showFullData,setShowFullData]=React.useState([]);

    // ページの横幅
    const [pageMinWidth,pageMaxWidth]=["min-w-250","max-w-350"];

      return {data, setData, post, processing, errors,clearErrors,reset,checkByDayItems,flattedData,thWidthSets,pageMinWidth,pageMaxWidth};
}
