// 各スタッフにおける選択したmapに対応したplan_idを取得する(formへの格納のため)
export default function SimpleDataFormatToFormData({planIdsAndMapsByMainProjects,choicedMap,selectedDate}){

    // スタッフごとのMapに対応するplanId(form用)
    let planIdsByStaffArrayForForm=[];

    // choicedMapに連動したplanIdを作成し、日付:スタッフ:planIdsの順で格納する
    Object.entries(choicedMap[selectedDate]).forEach(function([staff,eachSetsByStaff]){
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
        planIdsByStaffArrayForForm.push({"staffId":staff,"planIds":planIdsByStaffs.flat(2)})

    })//スタッフごと

    return planIdsByStaffArrayForForm;
}
