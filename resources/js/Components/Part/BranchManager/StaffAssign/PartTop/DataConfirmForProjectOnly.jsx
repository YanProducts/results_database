import SubmitOrBackButtons from "../../../../Common/SubmitOrBackButtons";
import BasePageHeader from "../../../../Common/BasePageHeader";
import ViewValidationErrors from "../../../../Common/ViewValidationErrors";
import TableByStaffsForConfirm from "../Inner/OnlyProject/TableByStaffsForConfirm";

// スタッフを割り当てた後のデータ確認ページ
export default function DataConfirmForProjectOnly({what,type,errors,pageMinWidth,pageMaxWidth,processing,selectedDate,staffs,choicedProjects,onConfirmOkClick,onConfirmCancelClick,flash}){

    const staffsInSelectedDate=staffs[selectedDate]

    return(
        <div className={`base_frame ${pageMinWidth} ${pageMaxWidth}`}>

        {/* 提出後の差し戻しではなく、単純に確認の時 */}
        {!flash ?
            <>
                <BasePageHeader {...{what,type,pageMaxWidth,pageMinWidth,subtitle:"以下でよろしいですか？"}}/>
                {/* バリデーションエラー */}
                <ViewValidationErrors errors={errors}/>

                {/* スタッフごとの表 */}
                <TableByStaffsForConfirm {...{selectedDate,pageMaxWidth,pageMinWidth,staffsInSelectedDate,choicedProjects}} />

                <p>　</p>
                {/* 提出or戻る */}
                <SubmitOrBackButtons minWidth={pageMinWidth} maxWidth={pageMaxWidth} processing={processing} errors={errors} onSubmitBtnClick={onConfirmOkClick} onCancelBtnClick={onConfirmCancelClick}/>

                <p>　</p>

            </>
          :
        //   差し戻しの時
           <>
            {/* <BasePageHeader {...{what,type,pageMaxWidth,pageMinWidth,subtitle:"以下の案件は全町目の配布データが存在しますが\n間違いありませんか？"}}/>
            <DuplicatedTownsTable {...{flash,pageMaxWidth,pageMinWidth,errors,processing}}/> */}
           </>
         }
    </div>
    )
}
