import BaseTable from "../../Common/BaseTable";
import { route } from "ziggy-js";
import { Link } from "@inertiajs/react";

// 案件一覧のテーブル
export default function OverviewTable({allData,pageMinWidth,pageMaxWidth,onDecideReport,staffId="",processing}){

    return(
        <BaseTable tableTheme="報告書一覧" width={"w-[97.5%]"}  thSets={{"date":"日付","projects":"案件(メインのみ)","cities":"市","count":"メイン合計配布枚数"}} maxWidth={pageMaxWidth} minWidth={pageMinWidth} allData={[]} mb={"mb-4"} >
            {Object.entries(allData).map(function([date,eachData],index){
                // 今日かどうかの確認
                const isToday=date!==new Date().toLocaleDateString("sv-SE");
   
                return(
                    <tr key={index} className={`border-black border-2 ${(isToday && eachData.data?.all_main_project_names && !processing) ? "cursor-pointer hover:bg-amber-200" : ""}`} onClick={isToday ? (e)=>onDecideReport({e,date,staffId}) : ()=>{}}>
                        <td className="border-black border-x-2">{eachData.dateInView}</td>
                        <td className="border-black border-x-2">{eachData.data?.all_main_project_names || "-"}</td>
                        <td className="border-black border-x-2">{eachData.data?.all_city_lists || "-"}</td>
                        <td className="border-black border-x-2">{eachData.data?.counts || (eachData.status== 1 ? "未提出" : "-")}</td>
                    </tr>
                )
            })
            }
        </BaseTable>
    )
}
