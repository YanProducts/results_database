// 入力担当が案件ごとに報告データを記入する際のテーブル
export default function TrForSum({dataInReport,totalPlannedCount}){

    return(
        <>
            {/* 合計計算と設定合計 */}
            <tr className="border-black border-2"><td className="border-black border-2" colSpan={2}>設定合計</td><td className="border-black border-2">{totalPlannedCount}</td></tr>
            <tr className="border-black border-2"><td className="border-black border-2" colSpan={2}>記入合計</td><td className="border-black border-2">{dataInReport.reduce((totalData,thisElement)=>(totalData+thisElement?.totalCount || 0),0)}</td></tr>
        </>
    )
}
