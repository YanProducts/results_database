import InputPageHeader from "../../../Common/InputPageHeader";
import BaseTable from "../../../Common/BaseTable";
import TrForSum from "./TableInner/TrForSum";
import SubmitOrBackButtons from "../../../Common/SubmitOrBackButtons";

// 入力担当編集の案件ごと報告書の確認
export default function ReportConfirm({what,type,processing,pageMinWidth,pageMaxWidth,projectSets,changedId,dataInReport,reportDataInTheProject,onConfirmOKClick,onConfirmCancelClick,totalPlannedCount}){
    return(
      <>
        <InputPageHeader what={what} type={type} minWidth={pageMinWidth} maxWidth={pageMaxWidth} inputWhat="必要な数値" needUserName={false} />

        {/* バリデーションエラー表示時はconfirm画面ではなくなっている */}

        <BaseTable tableTheme={Object.values(projectSets)[0]} width={"w-[97.5%]"} thSets={{"town":"町名","household":"世帯数","count":"配布数","place":"営業所","details":"詳細"}} thWidthSets={["w-[35%]","w-[9%]","w-[9%]","w-[12%]","w-[35%]"]} maxWidth={pageMaxWidth} minWidth={pageMinWidth} allData={[]} mb={"mb-4"}>
              {Object.entries(reportDataInTheProject).map(([planId,eachData],index)=>
                    <tr className={`${changedId.includes(planId) ? "border-2 border-sky-400" :"border border-black"}`}  key={planId}>
                        <td className="border-black border-2">{eachData.town_name}</td>
                        <td className="border-black border-2">{eachData.house_hold}</td>
                        <td className="border-black border-2">{dataInReport.find(eachReport=>(eachReport.planId==planId)).totalCount}</td>
                        <td className="border-black border-2">{eachData.place_name}</td>
                        <td className="border-black border-2 whitespace-pre-wrap">{eachData.detail_data}</td>
                    </tr>
                )}
            {/* 合計 */}
            <TrForSum {...{dataInReport,totalPlannedCount}}/>

        </BaseTable>

        {/* 提出ボタン */}
        <SubmitOrBackButtons {...{minWidth:pageMinWidth,maxWidth:pageMaxWidth,processing,onSubmitBtnClick:onConfirmOKClick,disabled:(changedId.length == 0),onCancelBtnClick:onConfirmCancelClick} }/>

      </>
    )
}
