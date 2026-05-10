import { route } from "ziggy-js"
import { Link } from "@inertiajs/react"

// 入力担当の案件確認におけるtr要素の中身
export default function TrInner({projectId,projectData,projectName,index,CSVOutputSets,onExportCheckChange,onCompleteCheckClick,isComplete}){
    return(
            <tr className="border border-black text-center" key={projectId}>
                <td className="border border-black">{projectName}</td>
                <td className="border border-black">{projectData.end_date}</td>
                <td className="border border-black">{projectData.planned_town_counts}</td>
                <td className="border border-black">{projectData.recorded_town_counts}</td>
                <td className="border border-black">{projectData.recorded_distribution_counts}</td>

                <td className="border border-black"><input id={`check_${index}`} type="checkbox" name="" checked={CSVOutputSets[projectName]?.isExport ?? false} onChange={(e)=>{onExportCheckChange(e,projectName,projectId)}}/><label htmlFor={`check_${index}`}>出力</label></td>

                {/* 案件完成フラグボタン */}
                <td className="border border-black"><div className="base_btn_div"><button className="bg-gray-200 border border-black cursor-pointer rounded-sm p-1 my-1" onClick={(e)=>{onCompleteCheckClick(e,projectName,projectId)}}>{isComplete[projectName]?.completeFlag ? "再編集" : "終了"}</button></div></td>

                <td className="border border-black"><Link className="cursor-pointer  text-blue-500 border-blue-500 border-b-2" href={route(`clerical.write_report`,{"edit_id":projectId})}><span>編集</span></Link></td>

            </tr>
    )
}
