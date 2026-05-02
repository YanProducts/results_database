import SubmitOrBackButtons from "../../../Common/SubmitOrBackButtons";
import AssignSetsForConfirming from "./Inner/AssignSetsForConfirming";
import BasePageHeader from "../../../Common/BasePageHeader";
import DuplicatedTownsTable from "./Inner/DuplicatedTownsTable";
import ViewValidationErrors from "../../../Common/ViewValidationErrors";

// スタッフを割り当てた後のデータ確認ページ
export default function DataConfirm({what,type,errors,pageMinWidth,pageMaxWidth,processing,selectedDate,onConfirmOkClick,onConfirmCancelClick,assignPlanForConfirmView,duplicatedCheck,flash,onDuplicatedOkClick}){

    return(
    <div className={`base_frame ${pageMinWidth} ${pageMaxWidth}`}>

    {!duplicatedCheck ?
        <>
        <BasePageHeader {...{what,type,pageMaxWidth,pageMinWidth,subtitle:"以下でよろしいですか？"}}/>

        {/* バリデーションエラー */}
        <ViewValidationErrors errors={errors}/>

        {/* スタッフ⇨案件名⇨町目セット */}
        <AssignSetsForConfirming {...{assignPlanForConfirmView,pageMinWidth,pageMaxWidth,selectedDate}} />

        <SubmitOrBackButtons minWidth={pageMinWidth} maxWidth={pageMaxWidth} processing={processing} errors={errors} onSubmitBtnClick={onConfirmOkClick} onCancelBtnClick=
       {onConfirmCancelClick}/>
       </>
    :
    <>
        {/* 重複があった場合 */}
        <BasePageHeader {...{what,type,pageMaxWidth,pageMinWidth,subtitle:"以下の町目が重複しています\nよろしいいですか？"}}/>
        <DuplicatedTownsTable duplicatedSets={flash.duplicated}/>
        <SubmitOrBackButtons minWidth={pageMinWidth} maxWidth={pageMaxWidth} processing={processing} onSubmitBtnClick={onDuplicatedOkClick} onCancelBtnClick=
       {onConfirmCancelClick}/>
    </>
    }
    </div>
    )
}
