import Layout from "../../../Layout/Layout";

// 重複しているデータの場合、OKかやり直すか
export default function DuplicatedConfirm({prefix,what,type,date}){

    return(
        <>
            {/* ヘッダー */}
             <BasePageHeader {...{what,type,pageMaxWidth,pageMinWidth,subtitle:"以下の町目が重複しておりますが、\n問題ないですか？"}}/>

            {/* 確認の表 */}
              <BaseTable tableTheme={`日付：${new Date(date).toLocaleDateString("ja-JP", {month: "long",day: "numeric"})}`} thSets={{"project":"案件名","town":"町目","staff":"担当予定スタッフ"}} allData={"カスタムなので除外"} pageMaxWidth={pageMaxWidth} pageMinWidth={pageMinWidth} width={"w-[80%]"}>

              {/* カスタムかどうかは今後判断 */}

              </BaseTable>
    </>

    )

}
