// 案件一覧におけるtr内部の捕捉
import { route } from "ziggy-js"
import { Link } from "@inertiajs/react"
import { formatDateForView } from "../../../../Support/Common/formatDateForView";

export default function TrInner({projectSets}){
    return(
            <tr className="border border-black text-center" key={projectSets["project_id"]+"_"+projectSets["another_project_flag"]}>
                {/* 案件名(同案件フラグは期間場合によっては案件自体も違うため違う案件とみなす) */}
                <td className="border-2 border-black">{projectSets["project_name"]}</td>
                <td className="border-2 border-black">{formatDateForView(projectSets["start_date"])}</td>
                <td className="border-2 border-black">{formatDateForView(projectSets["end_date"])}</td>
                <td className="border-2 border-black">{projectSets["town_count"]}</td>
                <td className="border-2 border-black">{projectSets["finished_town_count"]}</td>
                <td className="border-2 border-black">{projectSets["distribution_plan_count"]}</td>
                <td className="border-2 border-black">{projectSets["finished_distribution_count"]}</td>

                {/* 編集ボタン*/}
                <td className="border border-black"><Link className="cursor-pointer  text-blue-500 border-blue-500 border-b-2" href={route(`project_operator.edit_project_top`,{"edit_id":projectSets["project_id"] ?? 1})}><span>編集</span></Link></td>
            </tr>
    )
}
