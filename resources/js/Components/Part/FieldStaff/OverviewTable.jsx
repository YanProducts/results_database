import React from "react";
import BaseTable from "../../Common/BaseTable";
import { route } from "ziggy-js";
import { Link } from "@inertiajs/react";

// 案件一覧のテーブル
export default function OverviewTable({allData,pageMinWidth,pageMaxWidth}){
    return(
        <BaseTable tableTheme="報告書一覧" width={"w-[97.5%]"}  thSets={{"date":"日付","projects":"案件(メインのみ)","cities":"市","count":"メイン合計配布枚数"}} maxWidth={pageMaxWidth} minWidth={pageMinWidth} allData={[]} mb={"mb-4"} >
            {Object.entries(allData).map(([date,eachData],index)=>
            <tr key={index} className="border-black border-2">
                <td className="border-black border-x-2"><Link href={route("field_staff.show_detail_report",{date:"1"})}>{eachData.dateInView}</Link></td>
                <td className="border-black border-x-2">{eachData.data?.all_main_project_names || "-"}</td>
                <td className="border-black border-x-2">{eachData.data?.all_city_lists || "-"}</td>
                <td className="border-black border-x-2">{eachData.data?.counts || (eachData.status== 1 ? "未提出" : "-")}</td>
            </tr>
            )
            }
        </BaseTable>
    )
}
