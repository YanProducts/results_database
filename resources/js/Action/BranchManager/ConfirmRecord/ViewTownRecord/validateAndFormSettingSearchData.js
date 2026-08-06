// 検索で項目決定した時のバリデーション(UIでの)とフォームへのセット
export default function validateAndFormSettingSearchData({townChoiceMode,prefBySelect,cityBySelect,townBySelect,townByList,selectedStaffs,selectedStartYear,selectedEndYear,setData}){

        // 住所が未記入のとき
        if((townChoiceMode=="select" && (!prefBySelect || !cityBySelect)) || townChoiceMode=="list" && townByList.length==0){
            alert("住所が選択されていません");
            return;
        }

        // 記入モードが100町を超えた時(エクセルから貼り付けた際は改行の入力はされない)
        if(townChoiceMode=="list" && townByList.split("\n").length>100){
                alert("100町目以上は入力できません")
                return;
        }

       const dataSets={
            "staffIds":selectedStaffs,
            "startYear":selectedStartYear,
            "endYear":selectedEndYear
        }

        if(townChoiceMode=="list"){
            // リスト一覧からの投稿
            setData({
                ...dataSets,
                "pattern":"list",
                "addressNames":townByList.split("\n")
            })

        }else{
            // selectからの投稿
            // 町目をすべて選択にするか否かで県と市も渡すかを変更
            if(townBySelect==0){
                setData({
                    ...dataSets,
                    "pattern":"selectAll",
                    "prefName":prefBySelect,
                    "cityName":cityBySelect,
                })
            }else{
                 setData({
                    ...dataSets,
                    "pattern":"selectOneTown",
                    // idが挿入される
                    "addressId":townBySelect
                })
            }
        }
}
