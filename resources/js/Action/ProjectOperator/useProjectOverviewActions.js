import React from "react"
export default function useProjectOverviewActions({columnForHiddenLists,setHiddenListsVisible,setColumnForHiddenLists,setAllHiddenLists}){
    // どの値の行を表示しないかの設定
    React.useEffect(()=>{
        console.log(columnForHiddenLists)
        if(!columnForHiddenLists){
            return;
        }
        setHiddenListsVisible(true)
    },[columnForHiddenLists])

    // 何を表示させないかリストを表示
    // 共通tableの使用上、キーを日本語名で定義
    const onHiddenChangeClick=(thName)=>{
        setColumnForHiddenLists(thName)
    };

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
