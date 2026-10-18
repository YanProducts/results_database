import SelectPartsForViewChange from "../../../Common/SelectPartsForViewChange";
import InputPageHeader from "../../../Common/InputPageHeader";
import BaseButton from "../../../Common/BaseButton";
import StaffListsForSimple from "./Inner/Simple/StaffListsForSimple";
import TownByProjectsForExplainForSimple from "./Inner/Simple/TownByProjectsForExplainForSimple";

// スタッフを割り当てる際のデータ入力ページ
export default function DataInputForSimple({what,type,pageMinWidth,pageMaxWidth,onSubmitBtnClick,selectedDate,onSelectedDateChange,onClickDateReset,dateSets,assignedDays,
onMapChoiceClick,onMapDecide,onMapChoiceClose,
planIdsAndMapsByMainProjects,dateProjectsIndex,staffs,staffInChoice,popUpVisible,choicedMap,processing}){


// 最終的にキーがproject|roundNumber,値がproject:~回目
// dateProjectIndex...ここには[projectName=>,roundNumber=>]という形式で入れ子
// planIdsAndMapsByMainProjects...ここは[projectName=>roundNumber=>]という二重カッコ

return(
    <>
    {/* タイトル */}
    <InputPageHeader what={what} type={type} minWidth={pageMinWidth} maxWidth={pageMaxWidth} inputWhat="日付"/>

    {/* 投稿フォーム */}
    <form onSubmit={onSubmitBtnClick} className={`${pageMinWidth} ${pageMaxWidth} mx-auto`}>

            {selectedDate ?
            // selectedDateが設定されている時は地図の選択
              <>
                <StaffListsForSimple
                    staffsInSelectedDate={staffs[selectedDate]}
                    projectNameInTheDay={dateProjectsIndex[selectedDate]}
                    {...{selectedDate,pageMinWidth,pageMaxWidth,planIdsAndMapsByMainProjects,staffInChoice,popUpVisible,onMapChoiceClick,onMapDecide,
                    onMapChoiceClose,choicedMap,onClickDateReset,dateProjectsIndex}}
                />

                {/* projectごとの町の説明 */}
                {selectedDate &&
                <TownByProjectsForExplainForSimple {...{planIdsAndMapsByMainProjects,dateProjectsIndexInTheDate:dateProjectsIndex[selectedDate]}} />
                }

                <BaseButton processing={processing} disabled={Object.keys(choicedMap).length == 0 || popUpVisible}/>
             </>
            :
            // selectedDateが設定されていない時は日付選び
              <div className={`base_frame base_backColor md:p-1 p-0 border-2 border-black rounded-sm mb-2 max-w-150`}>

                {/* 5日後までの日付(select変化でメイン案件名変化) */}
                <SelectPartsForViewChange value={selectedDate} onChange={onSelectedDateChange} prefix={"日付："} keyValueSets={dateSets} disabled={selectedDate ? true :false} fixed={selectedDate ? true :false} fixContents={selectedDate ? new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"}) : ""} disabledLists={assignedDays} disabledReason="分割済"/>

              </div>
        }


        </form>
     </>
    )
}
