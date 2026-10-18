// 通常版にて、表示用のプロジェクト名の取得
// simpleではroundNumberが入れ子だが、表示版ではroundNumberもセットで取得している。
// キーは[projectName|roundNumber]の形で使いまわしているので、そのままにしたい
// 投稿用はplanIdで可能のため、Jsx用のキーに[projectName|roundNumber]を使い(日に関わらず一律になる:1ヶ月以内なら同案件のため、sameProjectNameを考慮する必要はない)、表示用のprojectName:回目をここで設定
export default function getProjectNameForView(projectsInTheSelectedDate){
    let countsByProjects={};
    let returnSets={}
    projectsInTheSelectedDate.forEach((eachProjectWithRoundNumber)=>{
        const projectName=eachProjectWithRoundNumber.split("|")[0];
        if(!Object.keys(countsByProjects).includes(projectName)){
            returnSets[eachProjectWithRoundNumber]=projectName;
            countsByProjects[projectName]=1;
        }else{
            // 2回目のものを:2と表記するため、足してから格納
            countsByProjects[projectName]+=1;
            returnSets[eachProjectWithRoundNumber]=projectName+":"+countsByProjects[projectName];
        }
    })
    return returnSets;
}
