// 他の案件も同じ値を記入する時
export default function applyOtherProjectToSameValueClick({mainProjectName,projectId,index,inputValues,setInputValues,assignDataToStaff,selectedDate}){

        // 参照しないコピー
        let inputMainProjectCopy=structuredClone(inputValues[mainProjectName])
            // それぞれのmainProjectにおけるキーの取得(これはassignId、つまり割り当て後の案件のキー:mainのみ)
            const mainKeys=Object.keys(inputMainProjectCopy);
            mainKeys.forEach(function(mainKey){
                // その市における情報セット
                const inputDataInTheTown=inputMainProjectCopy?.[mainKey] ?? {} ;
                // その日、そのスタッフ、そのメイン案件・そのmapに割り当てられたデータ
                // キーにはmap番号が振られているので、データはvalueに入っている
                Object.values(assignDataToStaff[selectedDate][mainProjectName].each_data).forEach(function(eachAssignedData){
                        // メイン案件の時
                    if(index==0){
                        // 今入力している町目のidにおける、Laravelから送られてきた値で参照される併配が記述された一式
                        const matchedAssignData=eachAssignedData.find(eachTownData=>eachTownData?.assign_id==mainKey)

                        // 違う地図の場合はmatchする案件が見つからないので次へ
                        if(!matchedAssignData){
                            return;
                        }

                        // そのsubsetsのidに含まれる併配にinputValuesのmainの値のコピー
                        matchedAssignData.sub_sets.forEach(function(eachSubId){
                                inputMainProjectCopy[mainKey]={
                                        ...inputDataInTheTown,
                                        // main案件が記入されていない併配案件は0にする
                                        [eachSubId]:inputDataInTheTown?.main || 0
                                    }
                          })
                    }else{
                        // 併配→メインを設定
                        // 現在操作中の併配案件名をID案件名で取得しているのがprojectId
                        inputMainProjectCopy[mainKey].main=inputMainProjectCopy[mainKey]?.[projectId.substring(2)] ?? 0
                    }
                  })
            })

        setInputValues(prev=>({
            ...prev,
            [mainProjectName]:inputMainProjectCopy
        }));
}
