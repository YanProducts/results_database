import { data } from "autoprefixer";
import { route } from "ziggy-js";
import React from "react";

// 過去の町々目データの確認
export default function useConfirmTownRecordActions({staffLists,setData,post,selectedStaffs,setSelectedStaffs,setSelectedStartYear,setSelectedEndYear,townChoiceMode,setTownChoiceMode,prefBySelect,setPrefBySelect,cityBySelect,setCityBySelect,townBySelect,setTownBySelect,townDataByList,setTownDataByList}){

    // dataに挿入されたら送信
    React.useEffect(()=>{
        // 初回の時など
        if(!data || Object.keys(data)==0){
            return;
        }

        // 投稿
        post(route("branch_manager.confirm_project_record_post"))

        // 初期化
        setData({});
        setTownBySelect("");
        setCityBySelect("");
        setPrefBySelect("");

    },[data])

    // 選択中のスタッフが変更されたとき(配列になる)
    const onSelectedStaffsChange=(e)=>{
        const target=e.currentTarget;
        const targetValue=target.value
        if(!selectedStaffs.includes(targetValue)){
            // 全員選択以外の時
            // 選択されていなければ全員選択があれば外した上で選択
            setSelectedStaffs([...selectedStaffs,targetValue])
        }else{
            // 全員選択以外で選択されていたら外す
            // 前の時点で配列の長さが1つの場合は全員選択に変更
            setSelectedStaffs(selectedStaffs.filter(eachSelectedStaff=>eachSelectedStaff!=targetValue))
        }
    }

    // 期限の変更
    const onSelectedStartYearChange=(e)=>{
        const target=e.target;
        setSelectedStartYear(target.value);
    }
    const onSelectedEndYearChange=(e)=>{
        const target=e.target;
        setSelectedEndYear(target.value);
    }



    // 町目の表示法の変更ボタンが押された時
    const onTwonChoiceModeChangeClick=()=>{
        if(townChoiceMode=="list"){
            setTownChoiceMode("select")
        }else{
            setTownBySelect("");
            setCityBySelect("");
            setPrefBySelect("");
            setTownChoiceMode("list")
        }
    }

    // 現在選択中の県の変更(selectの場合)
    const onPrefChange=(e)=>{
        const target=e.currentTarget;
        setTownBySelect("");
        setCityBySelect("");
        setPrefBySelect(target.value)
    }
    // 現在選択中の市の変更(selectの場合)
    const onCityChange=(e)=>{
        const target=e.currentTarget;
        setTownBySelect("all");
        setCityBySelect(target.value)
    }
    // 現在選択中の町の変更(selectの場合)
    const onTownChange=(e)=>{
        const target=e.currentTarget;
        setTownBySelect(target.value)
    }

    // 貼り付け版の値の変更
    const onAddressListsChange=(e)=>{
        const target=e.currentTarget;
        // 入力値をセット
        setTownDataByList(target.value);
    }


    // 提出ボタンが押されたとき
    const onDecideSearhDataClick=(e)=>{
        e.preventDefault();

        // 住所が未記入のとき
        if((townChoiceMode=="select" && (!prefBySelect || !cityBySelect)) || townChoiceMode=="list" && townBySelect.length==0){
            alert("住所が選択されていません");
            return;
        }


        if(townChoiceMode=="list"){
            // リスト一覧からの投稿
            setData({
                "staffIds":staffLists,
                "addressNames":townDataByList
            })

        }else{
            // selectからの投稿
            setData({
                "staffIds":staffLists,
                "addressIds":{
                    "pref":prefBySelect,
                    "city":cityBySelect,
                    "town":townBySelect
                }
            })
        }
    }

    return {onSelectedStaffsChange,onSelectedStartYearChange,onSelectedEndYearChange,onTwonChoiceModeChangeClick,onPrefChange,onCityChange,onTownChange,onAddressListsChange,onDecideSearhDataClick}

}
