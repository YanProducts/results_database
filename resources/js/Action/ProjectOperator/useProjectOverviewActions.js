import React from "react"
export default function useProjectOverviewActions({columnForHiddenLists,setColumnForHiddenLists,setAllHiddenLists}){

    // どの値の行を表示しないかの設定
    React.useEffect(()=>{
        if(!columnForHiddenLists){
            return;
        }

    },[columnForHiddenLists])

    // 共通tableの使用上、キーを日本語名で定義
    const onHiddenChangeClick={
        "案件名":(e)=>{setColumnForHiddenLists("projectName")},
        "開始日":(e)=>{setColumnForHiddenLists("startDate")},
        "終了日":(e)=>{setColumnForHiddenLists("endDate")},
        "割当済町目数":(e)=>{setColumnForHiddenLists("townCount")},
        "配布済町目数":(e)=>{setColumnForHiddenLists("finishedTownCount")},
        "設定部数":(e)=>{setColumnForHiddenLists("distributionPlanCount")},
        "現在配布部数":(e)=>{setColumnForHiddenLists("finishedDistributioncount")},
    }

    // 実際にsortが変更したとき
    const onHiddenListsChange=(e,column)=>{

        // 値そのままの時
        // if(){}

        setAllHiddenLists(prev=>({
            ...prev,
            [column]:[
                ...prev[column],
                [e.targetElement.value]
            ]
        }))

        //値を〜以上で区切る時

    }

    return {onHiddenChangeClick,onHiddenListsChange}
}
