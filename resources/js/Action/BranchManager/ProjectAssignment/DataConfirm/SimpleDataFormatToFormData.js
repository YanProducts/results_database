// 各スタッフにおける選択したmapに対応したplan_idを取得する(formへの格納のため)
export default function SimpleDataFormatToFormData({staffs,planIdsAndMapsByMainProjects,choicedMap,selectedDate}){

    // スタッフごとのMapに対応するplanId(form用)
    let planIdsByStaffArrayForForm=[];

    // choicedMapに連動したplanIdを作成し、日付:スタッフ:planIdsの順で格納する
    Object.entries(choicedMap[selectedDate]).forEach(function([staffName,eachSetsByStaff]){
    // スタッフごとのplanIdの取得
     const planIdsByStaffs=
      Object.entries(eachSetsByStaff).map(([projectName,eachSetsByProjectName])=>
        Object.entries(eachSetsByProjectName).map(function([roundNumber,numberLists]){
            // 日付・スタッフ・案件・roundNumber区切りですでに登録されているものに限り、plan_idの配列を返す
            if(Array.isArray(numberLists) && numberLists.length>0){

                // numberListsを数値の集合に変更(hasが高速のためsetにした方が良い)
                const selectedMapNumbers = numberLists.map(Number)

                // Laravelからのプロジェクトごとに区分けされた配列から、選択された地図番号リストを含むものを選択し、そのplanIdを配列で返す
                return Object.values(planIdsAndMapsByMainProjects?.[projectName]?.[roundNumber].map_plan_data).filter(v=>(selectedMapNumbers.includes(parseInt(v.map_number)))).map(v=>v.plan_ids)
            }else{
             //   projectを選択したが途中で消した場合はこれにあたる(後にflat()を行うと空の配列は削除)
             return [];
            }
        })//roundNumberごと
      )//案件ごと

        // スタッフごとにplanIdを格納
        // staffはIdを取得(findなら見つかった時点で検索終了なのでfilterより軽い、また?.[0]を行うことでundefinedでもエラーなく終了)
        // 重複確認でmapNumberの再取得を行うが、mapNumberは案件やroundNumberごとに分かれ、また重複確認まで戻る可能性も少ないので、ここでmapNumberは設定しない

        planIdsByStaffArrayForForm.push({"staffId":Object.entries(staffs[selectedDate]).find(([,staffNameFromObj])=>staffName==staffNameFromObj)?.[0],"planIds":planIdsByStaffs.flat(3)})

    })//スタッフごと

    return planIdsByStaffArrayForForm;
}
