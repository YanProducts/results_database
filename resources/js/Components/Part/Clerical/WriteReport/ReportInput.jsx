// 入力担当が案件全体の報告書を記入する際の報告書記入のJSX
import InputPageHeader from "../../../Common/InputPageHeader";
import ViewValidationErrors from "../../../Common/ViewValidationErrors";
import BaseTable from "../../../Common/BaseTable";
import SubmitOrBackButtons from "../../../Common/SubmitOrBackButtons";

export default function ReportInput({what,type,pageMinWidth,pageMaxWidth,errors,projectSets,reportDataInTheProject,changedId,dataInReport,onReportCountChange,onConfirmButtonClick,onStartOverClick,totalPlannedCount,processing}){
    return(

            <>
                <InputPageHeader what={what} type={type} minWidth={pageMinWidth} maxWidth={pageMaxWidth} inputWhat="必要な数値" needUserName={false} />

                {/* バリデーションエラー(post後にisConfirmが戻るため表示される) */}
                <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth}/>

                {/* 実際の報告書テーブル */}
                {/* 町名、世帯数、配布数（当然１種）、営業所名、詳細 */}
                <BaseTable tableTheme={Object.values(projectSets)[0]} width={"w-[97.5%]"} thSets={{"town":"町名","household":"世帯数","count":"配布数","place":"営業所","details":"詳細"}} thWidthSets={["w-[35%]","w-[9%]","w-[9%]","w-[12%]","w-[35%]"]} maxWidth={pageMaxWidth} minWidth={pageMinWidth} allData={[]} mb={"mb-4"}>
                {Object.entries(reportDataInTheProject).map(([planId,eachData],index)=>
                    <tr className={`${changedId.includes(planId) ? "border-2 border-sky-400" :"border border-black"}`}  key={planId}>
                        <td className="border-black border-2">{eachData.town_name}</td>
                        <td className="border-black border-2">{eachData.house_hold}</td>
                        <td className="border-black border-2"><input className="w-full bg-white rounded-xs text-right pr-2" value={dataInReport.find(eachReport=>(eachReport.planId==planId)).totalCount} onChange={(e)=>{onReportCountChange(e,planId)}}/></td>
                        <td className="border-black border-2">{eachData.place_name}</td>
                        <td className="border-black border-2">{eachData.detail_data}</td>
                    </tr>
                )}
                {/* 合計計算と設定合計 */}
                <tr className="border-black border-2"><td className="border-black border-2" colspan={2}>設定合計</td><td className="border-black border-2">{totalPlannedCount}</td></tr>
                <tr className="border-black border-2"><td className="border-black border-2" colspan={2}>記入合計</td><td className="border-black border-2">{dataInReport.reduce((totalData,thisElement)=>(totalData+thisElement?.totalCounts || 0),0)}</td></tr>
                </BaseTable>

                {/* 提出ボタン */}
                <SubmitOrBackButtons {...{minWidth:pageMinWidth,maxWidth:pageMaxWidth,processing,onSubmitBtnClick:(e)=>onConfirmButtonClick(e),disabled:(changedId.length == 0),onCancelBtnClick:onStartOverClick} }/>

            </>
    )
}
