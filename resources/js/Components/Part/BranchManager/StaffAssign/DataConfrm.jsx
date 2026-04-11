import SubmitOrBackButtons from "../../../Common/SubmitOrBackButtons";
import AssignSetsForConfirming from "./Inner/AssignSetsForConfirming";

// スタッフを割り当てた後のデータ確認ページ
export default function DataConfirm({what,type,pageMinWidth,pageMaxWidth,assignPlan,staffs,processing,onConfirmOkClick,onConfirmCancelClick}){

    return(
    <div className={`base_frame ${pageMinWidth} ${pageMaxWidth}`}>
        <p>　</p>
        <h1 className={`base_h base_h1 ${pageMaxWidth} ${pageMinWidth}`}>{what}-{type}-</h1>
        <h3 className={`base_frame text-center mb-4 ${pageMaxWidth} ${pageMinWidth}`}>以下でよろしいですか？</h3>
        {/* スタッフ⇨案件名⇨町目セット */}
        <AssignSetsForConfirming {...{assignPlan,staffs}} />

        <SubmitOrBackButtons minWidth={pageMinWidth} maxWidth={pageMaxWidth} processing={processing} onSubmitBtnClick={onConfirmOkClick} onCancelBtnClick=
       {onConfirmCancelClick}/>
    </div>
    )
}
