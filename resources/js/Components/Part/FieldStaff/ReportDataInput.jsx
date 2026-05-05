import InputPageHeader from "../../Common/InputPageHeader";
import SelectPartsForViewChange from "../../Common/SelectPartsForViewChange";
import ReportInner from "./Part/ReportInner";
import BaseButton from "../../Common/BaseButton";

// 報告書の記入
export default function ReportDataInput({what,type,pageMinWidth,pageMaxWidth,onSubmitBtnClick,selectedDate,onSelectedDateChange,dateSets,assignDataToStaff,inputValues,inputRefs,onAssignedInputChange,processing,isConfirm}){
  return(
    <>
      {/* タイトル */}
        <InputPageHeader what={what} type={type} minWidth={pageMinWidth} maxWidth={pageMaxWidth} inputWhat="以下"/>
        {/* 投稿フォーム */}
        <form onSubmit={onSubmitBtnClick} className={`${pageMinWidth} ${pageMaxWidth} mx-auto`}>
            {/* 日付の選択 */}
            <SelectPartsForViewChange value={selectedDate} onChange={onSelectedDateChange} prefix={"日付："} keyValueSets={dateSets} disabled={assignDataToStaff[selectedDate] ? true :false} fixed={assignDataToStaff[selectedDate] ? true :false} fixContents={assignDataToStaff[selectedDate] ? new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"}) : ""}/>

            {/* 報告書の入力 */}
            {selectedDate &&  (assignDataToStaff[selectedDate] ?
            // 報告書テーブルの内部
             <>
                <ReportInner {...{pageMinWidth,pageMaxWidth,assignDataToStaff,selectedDate,onAssignedInputChange,inputRefs,inputValues,isConfirm}} />
                {/* 提出ボタン */}
                <BaseButton processing={processing} disabled={Object.keys(inputValues).length == 0}/>
             </>
            :
              <div className={`text-center base_frame base_backColor ${pageMinWidth} ${pageMaxWidth} mb-3`}><p>案件は届いておりません</p></div>
            )}
        </form>
     </>
  )
}
