import setTdWidthByProjectCounts from "./setTdWidthByProjectCounts";
import getSumSetsInTable from "./getSumSetsInTable";


// テーブルごとにStateの変数やUIで最終的に使う要素の取得
// mapを何度も回さないために先に取得

export default function getTableSetsByMainProjects({assignDataToStaff,selectedDate,inputValues,issuedCount,returnedCount}){

        if(!selectedDate || !assignDataToStaff[selectedDate]){
            return [];
        }

        return(Object.entries(assignDataToStaff[selectedDate]).map(function(keyValueSets,index){

                const mainProjectName=keyValueSets[0];
                const projectSets=keyValueSets[1]["project_set"];

                return {
                        // プロジェクトの数に応じてthやtdの長さの変化
                        "widthSets":setTdWidthByProjectCounts(Object.keys(keyValueSets[1]["project_set"]).length),
                        "mainProjectName":mainProjectName,
                        "projectSets":projectSets,
                        "dataInEachMainProject":keyValueSets[1]["each_data"],
                        // 合計数の取得
                        "sumSets":getSumSetsInTable({projectSets,inputValues,issuedCount,returnedCount,mainProjectName})
                    }
        })
    )
}
