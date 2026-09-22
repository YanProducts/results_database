// 入力担当が案件全体の報告書を記入する際の報告書記入のJSX
import InputPageHeader from "../../../Common/InputPageHeader";
import ViewValidationErrors from "../../../Common/ViewValidationErrors";
import BaseTable from "../../../Common/BaseTable";
import SubmitOrBackButtons from "../../../Common/SubmitOrBackButtons";
import TrForSum from "./TableInner/TrForSum";

export default function ReportInput({what,type,pageMinWidth,pageMaxWidth,validationHidden,errors,projectSets,reportDataInTheProject,changedId,dataInReport,onReportCountChange,onConfirmButtonClick,onStartOverClick,totalPlannedCount,processing}){
    
    return(

            <>
                <InputPageHeader what={what} type={type} minWidth={pageMinWidth} maxWidth={pageMaxWidth} inputWhat="必要な数値" needUserName={false} />

                {/* バリデーションエラー(post後にisConfirmが戻るため表示される) */}
                <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth} validationHidden={validationHidden}/>

                {/* 実際の報告書テーブル */}
                {/* 町名、世帯数、配布数（当然１種）、営業所名、詳細 */}
                <BaseTable tableTheme={Object.values(projectSets)[0]} width={"w-[97.5%]"} thSets={{"town":"町名","household":"世帯数","count":"配布数","place":"営業所","details":"詳細"}} thWidthSets={["w-[35%]","w-[9%]","w-[9%]","w-[12%]","w-[35%]"]} maxWidth={pageMaxWidth} minWidth={pageMinWidth} allData={[]} mb={"mb-4"}>
                {Object.entries(reportDataInTheProject).map(([planId,eachData],index)=>
                    <tr key={planId}>
                        <td className="border-black border-2">{eachData.town_name}</td>
                        <td className="border-black border-2">{eachData.house_hold}</td>
                        <td className={`relative ${(changedId.map(n=>Number(n))).includes(Number(planId)) ? "border-4 border-sky-700" :"border-2 border-black"}`}><input className="absolute inset-x-0 top-[5%] w-full h-[90%] bg-white rounded-xs text-right" value={dataInReport.find(eachReport=>(eachReport.planId==planId)).totalCount} onChange={(e)=>{onReportCountChange(e,planId)}}/></td>
                        <td className="border-black border-2">{eachData.place_name}</td>
                        <td className="border-black border-2 whitespace-pre-wrap">{eachData.detail_data}</td>
                    </tr>
                )}

                 {/* 合計 */}
                 <TrForSum {...{dataInReport,totalPlannedCount}} />

                </BaseTable>

                {/* 提出ボタン */}
                <SubmitOrBackButtons {...{minWidth:pageMinWidth,maxWidth:pageMaxWidth,processing,onSubmitBtnClick:onConfirmButtonClick,disabled:(changedId.length == 0),onCancelBtnClick:onStartOverClick} }/>

            </>
    )
}
