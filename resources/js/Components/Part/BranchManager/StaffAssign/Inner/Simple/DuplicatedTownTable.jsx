import BaseTable from "../../../../../Common/BaseTable";
import SubmitOrBackButtons from "../../../../../Common/SubmitOrBackButtons";
// 簡易版の際に、すでに全町目を行き終えている場合の「間違いないか」の確認の表(すでに行き終えているmapを表示)
export default function DuplicatedTownsTable({flash,pageMaxWidth,pageMinWidth,errors,processing}){

    // テーブル

        {/* 確認の表 */}
        <BaseTable tableTheme={`日付：${new Date(date).toLocaleDateString("ja-JP", {month: "long",day: "numeric"})}`} thSets={{"project":"案件名","map":"地図番号","staff":"担当予定スタッフ"}} allData={"カスタムなので除外"} pageMaxWidth={pageMaxWidth} pageMinWidth={pageMinWidth} width={"w-[90%]"}>

        {/* カスタムかどうかは今後判断 */}
        <tr>

        </tr>

        </BaseTable>

    // 組み直す場合
     {/* 提出or戻る */}
    <SubmitOrBackButtons minWidth={pageMinWidth} maxWidth={pageMaxWidth} processing={processing} errors={errors}
    onSubmitBtnClick={()=>{}}
    onCancelBtnClick={()=>{}}
    // onSubmitBtnClick={onConfirmOkClick}
    // onCancelBtnClick={onConfirmCancelClick}
    />

}
