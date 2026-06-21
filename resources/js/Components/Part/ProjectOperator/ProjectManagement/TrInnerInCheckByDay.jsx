import { Link } from "@inertiajs/react";
// 日毎の案件テーブルのTr要素
export default function TrInnerCheckedByDay({flattedData}){
    return(
        flattedData.map((eachData,index)=>
            <tr className="border border-black text-center" key={index}>
                <td className="border-2 border-black">{eachData.startDate}</td>
                <td className="border-2 border-black">{eachData.placeName}</td>
                <td className="border-2 border-black">{eachData.mainProjectName}</td>
                <td className="border-2 border-black w-[5%]">{eachData.roundNumber}</td>
                <td className="border-2 border-black w-[15%]">{eachData.subLists}</td>
                <td className="border-2 border-black w-[15%]">{eachData.cityLists}</td>
                <td className="border-2 border-black">{eachData.endDate}</td>
                {/* リンク先未作成 */}
                <td className="border-2 border-black"><Link className="cursor-pointer  text-blue-500 border-blue-500 border-b-2" href={route(`project_operator.edit_project_top`,{"edit_id":1 ?? 1})}><span>編集</span></Link></td>
            </tr>
        )
    )
}
