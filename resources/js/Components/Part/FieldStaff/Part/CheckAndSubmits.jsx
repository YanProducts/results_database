import ViewAttentions from "../../../Common/ViewAttentions"
import SubmitOrBackButtons from "../../../Common/SubmitOrBackButtons"

// 記入合計にズレがないかチェックして、ないのであれば提出
export default function CheckAndSubmits({differenceExists,processing,inputValues,pageMaxWidth,pageMinWidth,onSubmitBtnClick,onStartOverClick,tableSets}){


    return(
        <>
        {differenceExists && <ViewAttentions message={"配布数と合計にズレがあります"} mb={"mb-3"}/>}

        {/* 提出ボタン */}
        <SubmitOrBackButtons {...{minWidth:pageMinWidth,maxWidth:pageMaxWidth,processing,onSubmitBtnClick:(e)=>onSubmitBtnClick(e,tableSets),disabled:(Object.keys(inputValues).length == 0 || differenceExists), cancelSentence:"日付選択からやり直す", onCancelBtnClick:onStartOverClick} }/>

        </>
    )

}
