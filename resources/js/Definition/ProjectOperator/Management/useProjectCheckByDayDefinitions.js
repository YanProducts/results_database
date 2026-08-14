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

    // ページの横幅
    const [pageMinWidth,pageMaxWidth]=["min-w-250","max-w-350"];

      return {data, setData, post, processing, errors,clearErrors,reset,checkByDayItems,flattedData,thWidthSets,pageMinWidth,pageMaxWidth};
}
