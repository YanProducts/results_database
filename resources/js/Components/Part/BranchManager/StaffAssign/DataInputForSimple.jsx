import SelectPartsForViewChange from "../../../Common/SelectPartsForViewChange";
import InputPageHeader from "../../../Common/InputPageHeader";
import BaseButton from "../../../Common/BaseButton";
import RadioButton from "../../../Common/RadioButton";
import MapLists from "./Inner/MapLists";
import TownLists from "./Inner/TownLists";
import StaffListsForSimple from "./Inner/StaffListsForSimple";

// スタッフを割り当てる際のデータ入力ページ
export default function DataInputForSimple({what,type,pageMinWidth,pageMaxWidth,onSubmitBtnClick,selectedDate,onSelectedDateChange,onClickDateReset,dateSets,
onMapChoiceClick,onMapDecide,
planIdsAndMapsByMainProjects,dateProjectsIndex,staffs,staffInChoice,popUpVisible,choicedMap}){

return(
    <>
    {/* タイトル */}
    <InputPageHeader what={what} type={type} minWidth={pageMinWidth} maxWidth={pageMaxWidth} inputWhat="日付"/>

    {/* 投稿フォーム */}
    <form onSubmit={onSubmitBtnClick} className={`${pageMinWidth} ${pageMaxWidth} mx-auto`}>

             <div className={`base_frame base_backColor md:p-1 p-0 border-2 border-black rounded-sm mb-2 max-w-150`}>

                {/* 5日後までの日付(select変化でメイン案件名変化) */}
                <SelectPartsForViewChange value={selectedDate} onChange={onSelectedDateChange} prefix={"日付："} keyValueSets={dateSets} disabled={selectedDate ? true :false} fixed={selectedDate ? true :false} fixContents={selectedDate ? new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"}) : ""}/>

              </div>

            {selectedDate &&
                <StaffListsForSimple
                    staffsInSelectedDate={staffs[selectedDate]}
                    projectNameInTheDay={dateProjectsIndex[selectedDate]}
                    {...{selectedDate,pageMinWidth,pageMaxWidth,planIdsAndMapsByMainProjects,staffInChoice,popUpVisible,onMapChoiceClick,onMapDecide,choicedMap}}
                />

            }

            {/* 提出ボタン */}
            {/* <BaseButton processing={processing} disabled={Object.keys(assignPlan).length == 0}/> */}

        </form>
     </>
    )
}
