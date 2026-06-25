import React from "react";
import isObject from "../../../../Support/Common/isObject";
import simpleGetProjectNameForView from "../../../../Support/BranchManager/simpleGetProjectNameForView";

// シンプル版の選択されたmapの表示
export default function SimpleChoicedMapListsForView({eachStaff,choicedMap,selectedDate,needBr=true}){

    // そのスタッフが選択している地図(案件&roundNumber別)
    const choicedMapsInTheStaff=choicedMap?.[selectedDate]?.[eachStaff];

    // オブジェクトの時のみ行う
    const choicedMapArrayForview=
    isObject(choicedMapsInTheStaff) ?
        // プロジェクトごと
            Object.entries(choicedMapsInTheStaff).map(([projectName,valueInProjectName])=>
            // roundNumberごと(正確なroundNumberは不要)
                Object.values(valueInProjectName).map(function(eachSet,roundNumberIndex){

                // そのプロジェクトがその回において1度ならroundNumberは表示しない
                // 他の部分と型を合わせる(他の部分はroundNumberの配列以降の入れ子)
                const projectNameForView=simpleGetProjectNameForView({valueInProjectName:Object.keys(valueInProjectName),projectName,roundNumberIndex})

                // roundNumberごとに「Map+数字」が連なった文字列を取得
                const mapsInTheRoundNumber=(eachSet.map(eachSetInTheRoundNumber=>"Map" + eachSetInTheRoundNumber).join("・"))

                // それを表示の案件名:取得した文字列で返す
                return {
                    "projectNameForView":projectNameForView,
                    "mapsInTheRoundNumber":mapsInTheRoundNumber
                }
            }) //roundNumberごと
        ) //projectNameごと。roundNumberごとの配列は入れ子になる
    : [];

    // roundNumberで入れ子になっているため、配列は1つ外に出す必要がある
   return(
        choicedMapArrayForview.length >0 ?
       choicedMapArrayForview.flat().map((eachMapInfo,indexInEachMapInfo)=>
            <React.Fragment key={indexInEachMapInfo}>
                <span className="font-bold">{eachMapInfo.projectNameForView}</span>
                <span>{`：${eachMapInfo.mapsInTheRoundNumber}`}</span>
                {indexInEachMapInfo!=choicedMapArrayForview.length-1 && needBr &&<br/>}
            </React.Fragment>
       )
       :
       <span>未選択</span>
   )

}
