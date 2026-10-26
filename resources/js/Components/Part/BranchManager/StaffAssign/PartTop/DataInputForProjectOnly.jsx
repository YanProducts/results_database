import SelectPartsForViewChange from "../../../../Common/SelectPartsForViewChange";
import InputPageHeader from "../../../../Common/InputPageHeader";
import BaseButton from "../../../../Common/BaseButton";
import StaffListsForOnlyProject from "../Inner/OnlyProject/StaffListsForOnlyProject";
import TownByProjectsForExplainForProjectOnly from "../Inner/OnlyProject/TownByProjectsForExplainForProjectOnly";

// 案件版のスタッフを割り当てる際のデータ入力ページ
export default function DataInputForProjectOnly({what,type,pageMinWidth,pageMaxWidth,onSubmitBtnClick,nonDataInTheDayFlag,selectedDate,onSelectedDateChange,onClickDateReset,dateSets,assignedDays,
onProjectChoiceClick,onProjectDecide,onProjectChoiceClose,
dateProjectsIndex,staffs,staffInChoice,popUpVisible,choicedProjects,processing}){

// 最終的には[date=>{satfId=>,projetcPlanIds=>}の形式で投稿]
return(
    <>
    {/* タイトル */}
    <InputPageHeader what={what} type={type} minWidth={pageMinWidth} maxWidth={pageMaxWidth} inputWhat="日付"/>

    {/* 投稿フォーム */}
    <form onSubmit={onSubmitBtnClick} className={`${pageMinWidth} ${pageMaxWidth} mx-auto`}>

            {selectedDate ?
            // selectedDateが設定されている時は地図の選択
              <>
                <StaffListsForOnlyProject
                    staffsInSelectedDate={staffs[selectedDate]}
                    projectSetsInTheDay={dateProjectsIndex[selectedDate]}
                    {...{selectedDate,pageMinWidth,pageMaxWidth,staffInChoice,popUpVisible,onProjectChoiceClick,onProjectDecide,
                    onProjectChoiceClose,choicedProjects,onClickDateReset,dateProjectsIndex}}
                />

                {/* projectごとの町の説明 */}
                <TownByProjectsForExplainForProjectOnly {...{dateProjectsIndexInTheDate:dateProjectsIndex[selectedDate]}} />

                <BaseButton processing={processing} disabled={Object.keys(choicedProjects).length == 0 || popUpVisible}/>
             </>
            :
            // selectedDateが設定されていない時は日付選び
              <div className={`base_frame base_backColor md:p-1 p-0 border-2 border-black rounded-sm mb-2 max-w-150`}>

                {/* 5日後までの日付(select変化でメイン案件名変化) */}
                <SelectPartsForViewChange value={selectedDate} onChange={onSelectedDateChange} prefix={"日付："} keyValueSets={dateSets} disabled={selectedDate ? true :false} fixed={selectedDate ? true :false} fixContents={selectedDate ? new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"}) : ""} disabledLists={assignedDays} disabledReason="分割済"/>

                {/* その日の案件が何もなかった時 */}
                {nonDataInTheDayFlag &&
                <p className={`base_backColor base_frame text-center my-1 w-[90%]`}>その日は案件が届いておりません</p>
                }



              </div>
        }


        </form>
     </>
    )
}
