import ToggleLists from "../../../Common/ToggleLists"
export default function ProjectPart({sameProjectsData,onProjectsCheckClick,data}){

    return(
        <div className={`base_backColor base_frame border-2 border-black  text-left mt-5 mb-10 p-2 min-w-140`}>
        <h2 className="base_h">１：案件データの重複</h2>
        {(sameProjectsData && Object.keys(sameProjectsData).length>0)?
        <>
          <p className="px-2">下記の案件が、前回の同名の案件から１ヶ月が経過しておりますが、同じ案件でしょうか？</p>
          <ToggleLists contents={sameProjectsData} onToggleListsChange={onProjectsCheckClick} formLists={data.newProjects} labelWhenTrue="新案件" labelWhenFalse="既存と同じ"/>
        </>
        :
        <p>前回の案件の終了予定日から1か月以上が経過している重複案件データはありません(1月以内の場合、自動的に同じ案件を見なされます)</p>
        }
      </div>
    )
}
