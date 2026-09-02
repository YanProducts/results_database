import ViewAttentions from "../../../Common/ViewAttentions"
import SubmitOrBackButtons from "../../../Common/SubmitOrBackButtons"
import React from "react";
import WriteReportContext from "../../../../Contexts/FieldStaffs/useWriteReportContexts";

// 記入合計にズレがないかチェックして、ないのであれば提出
export default function CheckAndSubmits({differenceExists,processing,inputValues,pageMaxWidth,pageMinWidth,onSubmitBtnClick,onStartOverClick,tableSets}){

    const isEdit=React.useContext(WriteReportContext);
    const cancelSentence=(isEdit ? "最初" : "日付選択") + "からやり直す";

    return(
        <>
        {differenceExists && <ViewAttentions message={"配布数と合計にズレがあります"} mb={"mb-3"}/>}

        {/* 提出ボタン */}
        <SubmitOrBackButtons {...{minWidth:pageMinWidth,maxWidth:pageMaxWidth,processing,onSubmitBtnClick:(e)=>onSubmitBtnClick(e,tableSets),disabled:(Object.keys(inputValues).length == 0 || differenceExists), cancelSentence, onCancelBtnClick:onStartOverClick} }/>

        </>
    )

}
