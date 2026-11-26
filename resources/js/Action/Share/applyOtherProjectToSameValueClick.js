import MainTdInner from "../../Components/Part/FieldStaff/Part/MainTdinner";
import useTargetChangeHandler from "../BranchManager/ReportManagement/Part/useTargetChangeHandler";

// 他の案件も同じ値を記入する時
export default function applyOtherProjectToSameValueClick({mainProjectName,projectId,index,inputValues,setInputValues,projectOnlyFlag=false,assignDataToStaff,selectedDate,isEdit=false,changedData={},setChangedData=()=>{}}){


        // 参照しないコピー
        let inputMainProjectCopy=structuredClone(inputValues[mainProjectName])

        //新しく変更するデータ
        //isEditの内部だけだが、letは外側で行わないとエラーがでる
        let newChangedData={};
        if(isEdit){
            newChangedData= structuredClone(changedData[mainProjectName])
        }

        // それぞれのmainProjectにおけるキーの取得(これはassignId、つまり割り当て後の案件のキー:mainのみ)
        const mainKeys=Object.keys(inputMainProjectCopy);
        mainKeys.forEach(function(mainKey){
            // その市における情報セット（すでにない場合のみ初期化）
            const inputDataInTheTown=inputMainProjectCopy?.[mainKey] ?? {} ;
            // 変化したかの探知(すでにない場合のみ初期化)
            const newChangedDataInTheTown= newChangedData[mainKey] ?? [];


            // 現時点でメインサブ両方の情報がない時はreturn(0を記入しない);
            // 初回投稿のみ(編集は初期でnullが設定)
            if(!isEdit && Object.keys(inputDataInTheTown).length==0){
                return;
            }



            // その日、そのスタッフ、そのメイン案件・そのmapに割り当てられたデータ
            // キーにはmap番号が振られているので、データはvalueに入っている
            Object.values(assignDataToStaff[selectedDate][mainProjectName].each_data).forEach(function(eachAssignedData){

                const identifier=projectOnlyFlag ? "plan_id" : "assign_id";

                    // メイン案件の時
                    if(index==0){
                        // 今入力している町目のidにおける、Laravelから送られてきた値で参照される併配が記述された一式
                        const matchedAssignData=eachAssignedData.find(eachTownData=>eachTownData?.[identifier]==mainKey)

                        // 違う地図の場合はmatchする案件が見つからないので次へ
                        if(!matchedAssignData){
                            return;
                        }

                        // 併配のidセットは編集か初回かで分ける
                        const sub_sets=isEdit ?  Object.keys(matchedAssignData.sub_sets): matchedAssignData.sub_sets

                        // そのsubsetsのidに含まれる併配にinputValuesのmainの値のコピー
                        sub_sets.forEach(function(eachSubId){

                            // 編集の場合、該当町のmainも該当町&該当サブ案件のsubがnullの場合は次へ(投稿の場合は初期のnullが存在しない)
                            if(isEdit && inputDataInTheTown.main==null && inputDataInTheTown[eachSubId]==null){
                                return;
                            }

                            // 現在の併配データとメインのものが同じでも次のループへ(eachSubIdは現存のものとして残る)
                            if((inputDataInTheTown?.[eachSubId] || 0) == (inputDataInTheTown?.main || 0)){
                                return;
                            }

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

                        // 併配にその町が含まれないものはreturn
                        if(!eachAssignedData.find(eachTownData=>Object.keys(eachTownData.sub_sets).includes(projectId.substring(2)))){
                            return;
                        }

                        // 現在のメインの枚数が、そもそも併配と同じものの場合は何もしない
                        if(inputDataInTheTown?.main==inputDataInTheTown?.[projectId.substring(2)]){
                            return;
                        }

                        // 該当の併配案件があるものはその案件の数を記入、前もって何もセットされていなければ０(併配担当外の町目はそもそも除去)
                        inputMainProjectCopy[mainKey].main=inputMainProjectCopy[mainKey]?.[projectId.substring(2)] ?? 0;

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
