// 選択された案件の表示
export default function ChoicedProjectListsForView({staffId,choicedProjects,selectedDate,projectSetsInTheDay,needBr=true}){

    // そのスタッフが選択している案件(idで取得)=同じ振られ方をした案件はそれぞれ1つに決まるの配列
    const choicedProjectsInTheStaff=choicedProjects?.[staffId];

   return(
        choicedProjectsInTheStaff ?
        <span className="font-bold">
        {choicedProjectsInTheStaff.map(eachProjectId=>
            projectSetsInTheDay["id_"+eachProjectId]?.project_name_for_view
        ).join("、")}
       </span>
       :<span>未選択</span>
   )

}
