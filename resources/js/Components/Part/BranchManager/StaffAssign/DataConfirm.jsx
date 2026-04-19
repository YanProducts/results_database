import SubmitOrBackButtons from "../../../Common/SubmitOrBackButtons";
import AssignSetsForConfirming from "./Inner/AssignSetsForConfirming";
import BasePageHeader from "../../../Common/BasePageHeader";
import DuplicatedTownsTable from "./Inner/DuplicatedTownsTable";

// スタッフを割り当てた後のデータ確認ページ
export default function DataConfirm({what,type,pageMinWidth,pageMaxWidth,processing,selectedDate,onConfirmOkClick,onConfirmCancelClick,assignPlanForConfirmView,duplicatedCheck,flash}){

    return(
    <div className={`base_frame ${pageMinWidth} ${pageMaxWidth}`}>

    {!duplicatedCheck ?
        <>
        <BasePageHeader {...{what,type,pageMaxWidth,pageMinWidth,subtitle:"以下でよろしいですか？"}}/>


        {/* スタッフ⇨案件名⇨町目セット */}
        <AssignSetsForConfirming {...{assignPlanForConfirmView,pageMinWidth,pageMaxWidth,selectedDate}} />

        <SubmitOrBackButtons minWidth={pageMinWidth} maxWidth={pageMaxWidth} processing={processing} onSubmitBtnClick={onConfirmOkClick} onCancelBtnClick=
       {onConfirmCancelClick}/>
       </>
    :
    <>
        {/* 重複があった場合 */}
        <BasePageHeader {...{what,type,pageMaxWidth,pageMinWidth,subtitle:"以下の町目が重複しています\nよろしいいですか？"}}/>
        <DuplicatedTownsTable duplicatedSets={flash.duplicated}/>
    </>
    }
    </div>
    )
}
