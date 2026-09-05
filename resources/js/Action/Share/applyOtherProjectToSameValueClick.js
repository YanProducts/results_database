import MainTdInner from "../../Components/Part/FieldStaff/Part/MainTdinner";
import useTargetChangeHandler from "../BranchManager/ReportManagement/Part/useTargetChangeHandler";

// 他の案件も同じ値を記入する時
export default function applyOtherProjectToSameValueClick({mainProjectName,projectId,index,inputValues,setInputValues,assignDataToStaff,selectedDate,isEdit=false,changedData={},setChangedData=()=>{}}){

        // 参照しないコピー
        let inputMainProjectCopy=structuredClone(inputValues[mainProjectName])

        //新しく変更するデータ
        //isEditの内部だけだが、letは外側で行わないとエラーがでる
        let newChangedData={};
        if(isEdit){
            newChangedData= structuredClone(changedData[mainProjectName])
        }

        console.log(isEdit)
        console.log(inputMainProjectCopy)
        console.log(mainProjectName)
        console.log(changedData) //まだない

        // それぞれのmainProjectにおけるキーの取得(これはassignId、つまり割り当て後の案件のキー:mainのみ)
        const mainKeys=Object.keys(inputMainProjectCopy);
        mainKeys.forEach(function(mainKey){
            // その市における情報セット（すでにない場合のみ初期化）
            const inputDataInTheTown=inputMainProjectCopy?.[mainKey] ?? {} ;
            // 変化したかの探知(すでにない場合のみ初期化)
            const newChangedDataInTheTown= newChangedData[mainKey] ?? [];

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

                        // 併配のidセットは編集か初回かで分ける
                        const sub_sets=isEdit ?  Object.keys(matchedAssignData.sub_sets): matchedAssignData.sub_sets

                        // そのsubsetsのidに含まれる併配にinputValuesのmainの値のコピー
                        sub_sets.forEach(function(eachSubId){
                                inputMainProjectCopy[mainKey]={
                                        ...inputDataInTheTown,
                                        // main案件が記入されていない併配案件は0にする
                                        [eachSubId]:inputDataInTheTown?.main || 0
                                    }
                            // changeのフラグを変化させる
                            if(isEdit){
                                if(!(newChangedDataInTheTown.map(n=>Number(n))).includes(Number(eachSubId))){
                                   // 新たに変化したテーブルのUIの変化の大元の変数に挿入
                                    newChangedData[mainKey]=[...newChangedDataInTheTown, Number(eachSubId)]
                                }
                            }
                          })
                    }else{
                        // 併配→メインを設定
                        // 現在操作中の併配案件名をID案件名で取得しているのがprojectId
                        // 該当の併配案件があるものはその案件の数を記入、ないものは今の値のまま。前もって何もセットされていなければ０
                        inputMainProjectCopy[mainKey].main=inputMainProjectCopy[mainKey]?.[projectId.substring(2)] ?? (inputMainProjectCopy[mainKey].main ?? 0)
                        // changeのフラグを変化させる
                        // かなり冗長になるため一括でやるべき！！！！
                        if(isEdit){
                            // changeのフラグを変化させる
                                if(!newChangedDataInTheTown.includes("main")){
                                    // 新たに変化したテーブルのUIの変化の大元の変数に挿入
                                    newChangedData[mainKey]=[...newChangedDataInTheTown, "main"]
                                }
                        }
                    }
                  })
            })

        // 編集の場合は変化した値の箇所を反転
        if(isEdit){
            // newChangedDataは元の値に変化したところのみを加えた値
            setChangedData(prev=>({
            ...prev,
            [mainProjectName]:newChangedData
             }));
        }

        // inputの値の変化
        setInputValues(prev=>({
            ...prev,
            [mainProjectName]:inputMainProjectCopy
        }));
}
