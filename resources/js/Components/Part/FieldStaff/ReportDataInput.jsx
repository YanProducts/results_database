import InputPageHeader from "../../Common/InputPageHeader";
import SelectPartsForViewChange from "../../Common/SelectPartsForViewChange";
import ReportInner from "./Part/ReportInner";
import ViewValidationErrors from "../../Common/ViewValidationErrors";
import CheckAndSubmits from "./Part/CheckAndSubmits";
import BaseLinkLine from "../../Common/BaseLinkLine";

// 報告書の記入
export default function ReportDataInput({what,type,pageMinWidth,pageMaxWidth,staff,selectedDate,onSelectedDateChange,submittedDates=[],issuedCount,returnedCount,onIssuedOrReturnedCountsChange,setIssuedCount,setReturnedCount,dateSets,assignDataToStaff,inputValues,inputRefs,onAssignedInputChange,onInputKeyDown,tableSets,onSubmitBtnClick,onStartOverClick,differenceExists,errors,processing,isConfirm,changedData=null,fromSimpleFlag}){


    console.log(assignDataToStaff);


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
            {selectedDate &&
             (Object.keys(assignDataToStaff[selectedDate]).length>0 ?
             <>
                <ReportInner {...{pageMinWidth,pageMaxWidth,issuedCount,returnedCount,setIssuedCount,setReturnedCount,onIssuedOrReturnedCountsChange,onAssignedInputChange,onInputKeyDown,inputRefs,inputValues,tableSets,processing,isConfirm,fromSimpleFlag,changedData,selectedDate}} />

                {/* ズレの確認&問題ない時は送信 */}
                <CheckAndSubmits {...{differenceExists,processing,inputValues,pageMaxWidth,pageMinWidth,onSubmitBtnClick,onStartOverClick,tableSets}} />

                <p>　</p>
             </>
            //  提出済もしくは届いていない場合
            :
              <div className={`text-center base_frame  ${pageMinWidth} ${pageMaxWidth} mb-1`}>
                 {submittedDates.includes(selectedDate) ?
                    <p className={`font-bold text-red-600 mb-0 base_backColor`}>すでにデータは投稿されております</p>
                 :
                    <p className="mb-0 base_backColor">案件は届いておりません</p>
                 }
                <div className="my-2"><button className="border border-black base_btn mt-0" onClick={onStartOverClick}>日付を再選択</button></div>
              </div>
            )}
        </form>
     </>
  )
}
