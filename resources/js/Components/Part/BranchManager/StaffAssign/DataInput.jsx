import SelectPartsForViewChange from "../../../../Components/Common/SelectPartsForViewChange";
import InputPageHeader from "../../../../Components/Common/InputPageHeader";
import ViewValidationErrors from "../../../../Components/Common/ViewValidationErrors";
import BaseButton from "../../../../Components/Common/BaseButton";
import RadioButton from "../../../../Components/Common/RadioButton";
import MapLists from "./Inner/MapLists";
import TownLists from "./Inner/TownLists";

// スタッフを割り当てる際のデータ入力ページ
export default function DataInput({what,type,pageMinWidth,pageMaxWidth,onSubmitBtnClick,selectedDate,onSelectedDateChange,dateSets,selectedMainProject,onSelectedMainProjectChange,projectsAndTowns,onChangeMapOrTown,needNumber,selectedMapNumber,staffs,handleAssignChangeInMaps,assignPlan,handleAssignChangeInTowns,errors,processing}){
    return(
            <>
    {/* タイトル */}
    <InputPageHeader what={what} type={type} minWidth={pageMinWidth} maxWidth={pageMaxWidth} inputWhat="以下"/>

    {/* 投稿フォーム */}
    <form onSubmit={onSubmitBtnClick} className={`${pageMinWidth} ${pageMaxWidth} mx-auto`}>
             <div className={`base_frame base_backColor md:p-3 sm:p-2 p-0 border-2 border-black rounded-sm mb-5 `}>

                {/* 5日後までの日付(select変化でメイン案件名変化) */}
                <SelectPartsForViewChange value={selectedDate} onChange={onSelectedDateChange} prefix={"日付："} keyValueSets={dateSets}/>

                {/* メイン案件名(クリックすれば「MapNo選択⇨必要なら修正」or「町目リストから直接」の項目開く) */}
                <SelectPartsForViewChange value={selectedMainProject} onChange={onSelectedMainProjectChange} prefix={"メイン案件名："} keyValueSets={Object.fromEntries(Object.keys(projectsAndTowns).map(project=>[project,project]))}/>

                {/* 案件ナンバーと町目リストのどちらから選ぶか */}
                <RadioButton onChange={(e)=>{onChangeMapOrTown(e)}} minWidth="min-w-80" maxWitdh="w-[100%]"
                contentsSets={[{"prefix":"地図番号から選ぶ","value":"mapNumber"},{"prefix":"町目リストから選ぶ","value":"townList"}]} radioName="mapOrTown" stateForSelected={needNumber}/>

              </div>

            {/* 振り分けはmapNumberと町目リストのどちらで行うか */}
            {needNumber==="mapNumber" ?
                // 地図リスト表示
                <MapLists projectsAndTowns={projectsAndTowns} selectedMainProject={selectedMainProject} selectedMapNumber={selectedMapNumber} staffs={staffs} handleAssignChangeInMaps={handleAssignChangeInMaps}/>
            :
                // 町目リスト表示
                <TownLists projectsAndTowns={projectsAndTowns} selectedMainProject={selectedMainProject} assignPlan={assignPlan} staffs={staffs} handleAssignChangeInTowns={handleAssignChangeInTowns}/>
            }

            {/* バリデーションエラー */}
            <ViewValidationErrors errors={errors} />

            {/* 提出ボタン */}
            <BaseButton processing={processing}/>

        </form>
     </>
    )
}
