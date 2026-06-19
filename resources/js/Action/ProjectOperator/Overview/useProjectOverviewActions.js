import React from "react";
import popUpPositionSeeting from "../../../Support/Common/popUpPositionSetting";
import sortData from "../../../Support/Common/sortData";
export default function useProjectOverviewActions({projectData,overViewItems,setSortItemIsVisible,selectedSort,setSelectedSort,prioritySort,setPrioritySort,selectedAscOrDes,setSelectedAscOrDes,ascOrDes,setAscOrDes,columnForHiddenLists,setHiddenListsVisible,setColumnForHiddenLists,setAllHiddenLists,setShowFullData}){

    // sort項目の決定
    // useMemoは状態変数が変わるごとにレンダリング1回で変数を定義し直す(effectで更新してからstate更新なら2回になる)
    const sortedProjectData=React.useMemo(()=>{
        return sortData({...{prioritySort,ascOrDes,projectData},objForDataCheck:overViewItems,sourceData:projectData});
    },[projectData,prioritySort,ascOrDes])

    // どの値の行を表示しないかの設定
    React.useEffect(()=>{
        if(!columnForHiddenLists){
            // 何を表示しないかのリストを非表示にする
            setHiddenListsVisible(false)
            return;
        }
        setHiddenListsVisible(true)
    },[columnForHiddenLists])


    // ソートの変更がクリックされた時(そもそもの項目リストを出す)
    const onSortChangeClick=(e)=>{
        // 座標の変化(js)
        popUpPositionSeeting(e,"popUpSortLists",-10,5);
        // 表示
        setSortItemIsVisible(true);
    }
    // ソート変更を閉じるボタンのクリック(項目リスト自体を閉じる)
    const onSortChangeClose=()=>{
        setSortItemIsVisible(false);
        // 初期選択を決定版に戻す
        setSelectedSort(prioritySort);
        setSelectedAscOrDes(ascOrDes);
    }
    // 昇順降順の変更クリック
    const onAscOrDesClick=(e)=>{
        const target=e.currentTarget;
        setSelectedAscOrDes(target.value)
    }
    //ソート変更のクリック
    const onSortKindChange=(e)=>{
        const target=e.currentTarget;
        setSelectedSort(target.value);
    }

    // ソートの決定
    const onSortChangeDecide=()=>{
        //selectedSortの値で並び替え、ソート変更のリストを閉じる
        setAscOrDes(selectedAscOrDes)
        setPrioritySort(selectedSort)
        setSortItemIsVisible(false);
    }


    // 何を表示させないかリストを表示
    // 共通tableの使用上、キーを日本語名で定義
    const onHiddenChangeClick=(e,thName)=>{
        // 座標の変化(js)
        popUpPositionSeeting(e,"popUpHiddenLists",10,5);

        // stateを変化させて表示させる
        setColumnForHiddenLists(thName);
    };

    // 実際にsortが変更したとき
    const onHiddenListsChange=(e,column)=>{
        setAllHiddenLists(prev=>({
            ...prev,
            [column]:[
                ...prev[column],
                [e.targetElement.value]
            ]
        }))

        //値を〜以上で区切る時
        alert("並び替えは未作成です")

    }

    // 全表示のチェック
    const onShowFullDataChange=(e,columnForHiddenLists)=>{
        setShowFullData(prev=>[...prev,columnForHiddenLists])
    }

    // ボタンを閉じた時
    const onEachHiddenCloseClick=()=>{
        // stateを非表示にする
        // ここでcolumnから値を消し、useEffectで非表示のtailwindに設定
        setColumnForHiddenLists("")
    }

    return {sortedProjectData,onSortChangeClick,onSortChangeClose,onAscOrDesClick,onSortKindChange,onSortChangeDecide,onHiddenChangeClick,onHiddenListsChange,onShowFullDataChange,onEachHiddenCloseClick}
}
