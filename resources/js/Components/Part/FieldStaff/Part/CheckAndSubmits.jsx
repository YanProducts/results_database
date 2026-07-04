import ViewAttentions from "../../../Common/ViewAttentions"
import BaseButton from "../../../Common/BaseButton"

// 記入合計にズレがないかチェックして、ないのであれば提出
export default function CheckAndSubmits({differenceExists,processing,inputValues}){


    return(
        <>
        {differenceExists && <ViewAttentions message={"配布数と合計にズレがあります"} mb={"mb-3"}/>}

        {/* 提出ボタン */}
        <BaseButton processing={processing} disabled={Object.keys(inputValues).length == 0 || differenceExists}/>
        </>
    )

}
