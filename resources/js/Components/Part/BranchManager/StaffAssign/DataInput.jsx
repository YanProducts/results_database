import SelectPartsForViewChange from "../../../../Components/Common/SelectPartsForViewChange";
import InputPageHeader from "../../../../Components/Common/InputPageHeader";
import BaseButton from "../../../../Components/Common/BaseButton";
import RadioButton from "../../../../Components/Common/RadioButton";
import MapLists from "./Inner/Detail/MapLists";
import TownLists from "./Inner/Detail/TownLists";

// スタッフを割り当てる際のデータ入力ページ
export default function DataInput({what,type,pageMinWidth,pageMaxWidth,onSubmitBtnClick,selectedDate,onSelectedDateChange,onClickDateReset,dateSets,assignedDays,selectedMainProject,onSelectedMainProjectChange,projectsAndTowns,dateProjectsIndex,onChangeMapOrTown,needNumber,mapMeta,staffs,handleAssignChangeInMaps,assignPlan,handleAssignChangeInTowns,processing}){

 //複数回使用するので前もって定義
 const projectsInTheSelectedDate=selectedDate ? Object.values(dateProjectsIndex[selectedDate]) : [];
// というか、この時点で、表示用をセットする必要がある
        // このターンでのプロジェクト名のセット(roundNumberつき)
        const projectRoundNumberArray=projectRoundNumberSet?.split("|");
        // これを[キーはそのまま通算の回数(キーで統一):値をプロジェクトNameの今回の数に合わせた何番目か変化させる]

                    // // 同じprojectの名前をまとめる(1月内のものは必ず同じ案件なのでsame_project_flagは必要ない)
                    // const sameProjectSets
                    // projectsInTheSelectedDate.filter((eachProject)=>
                    //     eachProject.split("|")==projectRoundNumberArray[0]
                    // );



                    // // これもっと前の段階で変える必要あり！！！！！
                    // // 最初に行う！？


                    // const roundNumberIndex=projectRoundNumberArray[1];


 return(
    <>

    {/* タイトル */}
    <InputPageHeader what={what} type={type} minWidth={pageMinWidth} maxWidth={pageMaxWidth} inputWhat="以下"/>

    {/* 投稿フォーム */}
    <form onSubmit={onSubmitBtnClick} className={`${pageMinWidth} ${pageMaxWidth} mx-auto`}>
             <div className={`base_frame base_backColor md:p-3 sm:p-2 p-0 border-2 border-black rounded-sm mb-5 max-w-150`}>

                {/* 5日後までの日付(select変化でメイン案件名変化) */}
                <SelectPartsForViewChange value={selectedDate} onChange={onSelectedDateChange} prefix={"日付："} keyValueSets={dateSets} disabled={selectedDate ? true :false} fixed={selectedDate ? true :false} fixContents={selectedDate ? new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"}) : ""}  disabledLists={assignedDays} disabledReason="分割済"/>

                {/* メイン案件名(クリックすれば「MapNo選択⇨必要なら修正」or「町目リストから直接」の項目開く) */}
                <SelectPartsForViewChange value={selectedMainProject} onChange={onSelectedMainProjectChange} prefix={"メイン案件名："} keyValueSets={selectedDate ? Object.fromEntries(projectsInTheSelectedDate).map
                (function(projectRoundNumberSet){


                    // 現時点では[projectName|roundNumber]で来てる
                    // そに日に来ている当該projectNameの案件数と、そのroundNumberが何番目かの取得

                    return(
                        // キーと値のオブジェクトで返す(object.fromEntriesだから？)
                        ""
                        // [project+"|"+roundNumberIndex || "",roundNumberIndex==1 ? project : project + ":" + roundNumberIndex]

                    )}
                ) : {}}

                disabled={selectedDate ? false :true} opacity={selectedDate ? "opacity-100" : "opacity-30"}/>

                {/* 案件ナンバーと町目リストのどちらから選ぶか */}
                <RadioButton onChange={(e)=>{onChangeMapOrTown(e)}} minWidth="min-w-80" maxWitdh="max-w-100"
                contentsSets={[{"prefix":"地図番号から選ぶ","value":"mapNumber"},{"prefix":"町目リストから選ぶ","value":"townList"}]} radioName="mapOrTown" stateForSelected={needNumber}/>

                {selectedDate && <div className={`base_frame base_backColor text-center  max-w-150`}>日程を選択し直す場合は<span className="text-blue-600 font-bold cursor-pointer underline underline-offset-4" onClick={onClickDateReset}>こちら</span></div>}

              </div>
            {/* 日付を選択 */}


            {/* 振り分けはmapNumberと町目リストのどちらで行うか */}
            {selectedDate ?
             (needNumber==="mapNumber" ?
                // 地図リスト表示
                <MapLists projectsAndTowns={projectsAndTowns} selectedDate={selectedDate} selectedMainProject={selectedMainProject} mapMeta={mapMeta} staffs={staffs} handleAssignChangeInMaps={handleAssignChangeInMaps}/>
            :
                // 町目リスト表示
                <TownLists projectsAndTowns={projectsAndTowns} selectedDate={selectedDate} selectedMainProject={selectedMainProject} assignPlan={assignPlan} staffs={staffs} handleAssignChangeInTowns={handleAssignChangeInTowns}/>
             )
            :
                <div></div>
            }

            {/* 提出ボタン */}
            <BaseButton processing={processing} disabled={Object.keys(assignPlan).length == 0}/>

        </form>
     </>
    )
}
