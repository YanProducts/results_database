import InputPageHeader from "../../Common/InputPageHeader";
import SelectPartsForViewChange from "../../Common/SelectPartsForViewChange";
import ReportInner from "./Part/ReportInner";
import ViewValidationErrors from "../../Common/ViewValidationErrors";
import CheckAndSubmits from "./Part/CheckAndSubmits";

// 報告書の記入
export default function ReportDataInput({what,type,pageMinWidth,pageMaxWidth,staff,selectedDate,onSelectedDateChange,issuedCount,returnedCount,onIssuedOrReturnedCountsChange,setIssuedCount,setReturnedCount,dateSets,assignDataToStaff,inputValues,inputRefs,onAssignedInputChange,onInputKeyDown,tableSets,onSubmitBtnClick,onStartOverClick,differenceExists,errors,processing,isConfirm,fromSimpleFlag}){

  return(
    <>
      {/* タイトル */}
        <InputPageHeader what={what} type={type} minWidth={pageMinWidth} maxWidth={pageMaxWidth} inputWhat="以下" needUserName={true} userName={staff} />

        {/* バリデーションエラー(post後にisConfirmが戻るため表示される) */}
        <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth}/>

        {/* 投稿フォーム */}
        <form className={`${pageMinWidth} ${pageMaxWidth} mx-auto`}>
            {/* 日付の選択 */}
            <SelectPartsForViewChange value={selectedDate} onChange={onSelectedDateChange} prefix={"日付："} keyValueSets={dateSets} disabled={assignDataToStaff[selectedDate] ? true :false} fixed={assignDataToStaff[selectedDate] ? true :false} fixContents={assignDataToStaff[selectedDate] ? new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"}) : ""}
            afterSelectDivOption="bg-yellow-300 border-2 border-black rounded-sm" />

            {/* 報告書の入力 */}
            {selectedDate &&  (assignDataToStaff[selectedDate] ?
            //
             <>
                <ReportInner {...{pageMinWidth,pageMaxWidth,issuedCount,returnedCount,setIssuedCount,setReturnedCount,onIssuedOrReturnedCountsChange,onAssignedInputChange,onInputKeyDown,inputRefs,inputValues,tableSets,processing,isConfirm,fromSimpleFlag}} />

                {/* ズレの確認&問題ない時は送信 */}
                <CheckAndSubmits {...{differenceExists,processing,inputValues,pageMaxWidth,pageMinWidth,onSubmitBtnClick,onStartOverClick,tableSets}} />

                <p>　</p>
             </>
            :
              <div className={`text-center base_frame base_backColor ${pageMinWidth} ${pageMaxWidth} mb-3`}><p>案件は届いておりません</p></div>
            )}
        </form>
     </>
  )
}
