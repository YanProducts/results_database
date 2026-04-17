import SubmitOrBackButtons from "../../../Common/SubmitOrBackButtons";
import AssignSetsForConfirming from "./Inner/AssignSetsForConfirming";
import BasePageHeader from "../../../Common/BasePageHeader";

// スタッフを割り当てた後のデータ確認ページ
export default function DataConfirm({what,type,pageMinWidth,pageMaxWidth,processing,selectedDate,onConfirmOkClick,onConfirmCancelClick,assignPlanForConfirmView}){

    return(
    <div className={`base_frame ${pageMinWidth} ${pageMaxWidth}`}>

        <BasePageHeader {...{what,type,pageMaxWidth,pageMinWidth,subtitle:"以下でよろしいですか？"}}/>


        {/* スタッフ⇨案件名⇨町目セット */}
        <AssignSetsForConfirming {...{assignPlanForConfirmView,pageMinWidth,pageMaxWidth,selectedDate}} />

        <SubmitOrBackButtons minWidth={pageMinWidth} maxWidth={pageMaxWidth} processing={processing} onSubmitBtnClick={onConfirmOkClick} onCancelBtnClick=
       {onConfirmCancelClick}/>
    </div>
    )
}
