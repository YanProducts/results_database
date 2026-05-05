import BasePageHeader from "../../Common/BasePageHeader";
import ReportInner from "./Part/ReportInner";
import SubmitOrBackButtons from "../../Common/SubmitOrBackButtons";

// 報告書の確認
export default function ReportConfirm({what,type,pageMaxWidth,pageMinWidth,assignDataToStaff,selectedDate,inputRefs,inputValues,onAssignedInputChange,onConfirmOkClick,onConfirmCancelClick,errors,processing,isConfirm}){

    return(
        <>
        <BasePageHeader {...{what,type,pageMaxWidth,pageMinWidth,subtitle:"以下でよろしいですか？"}}/>

        {/* /報告書テーブルの内部 */}
        <ReportInner {...{pageMinWidth,pageMaxWidth,assignDataToStaff,selectedDate,onAssignedInputChange,inputRefs,inputValues,processing,isConfirm}} />

        {/* 提出もしくはやり直す */}
        <SubmitOrBackButtons minWidth={pageMinWidth} maxWidth={pageMaxWidth} processing={processing} errors={errors} onSubmitBtnClick={onConfirmOkClick} onCancelBtnClick=
        {onConfirmCancelClick}/>

        </>
    )
}
