// 選択された案件の表示
export default function ChoicedProjectListsForView({eachStaff,choicedProject,selectedDate,projectSetsInTheDay,needBr=true}){

    // そのスタッフが選択している案件(idで取得)=同じ振られ方をした案件はそれぞれ1つに決まるの配列
    const choicedProjectsInTheStaff=choicedProject?.[selectedDate]?.[eachStaff];

   return(
        choicedProjectsInTheStaff ?
        choicedProjectsInTheStaff.map(eachProjectId=>
            <span key={eachProjectId} className="font-bold">{        projectSetsInTheDay[eachProjectId]?.project_name_for_view}</span>
       )
       :<span>未選択</span>
   )

}
