import Layout from "../../../Layout/Layout"
import { RoleLayout } from "../../../Layout/RoleLayout"
import useProjectOverviewDefinitions from "../../../Definition/ProjectOperator/useProjectOverviewDefinitions";
import useProjectOverviewActions from "../../../Action/ProjectOperator/useProjectOverviewActions";
import BasePageHeader from "../../../Components/Common/BasePageHeader";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";
import BaseTable from "../../../Components/Common/BaseTable";
import TrInner from "../../../Components/Part/ProjectOperator/ProjectManagement/TrInner";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import HiddenList from "../../../Components/Part/ProjectOperator/ProjectManagement/HiddenList";

// 投稿したプロジェクトの一覧表示
export default function ProjectOverview({prefix,what,type,projectData}){

    // 定義
    const {data, setData, post, processing, errors,clearErrors, reset,columnForHiddenLists,setColumnForHiddenLists,prioritySort,setPrioritySortallHiddenLists,setAllHiddenLists,pageMinWidth,pageMaxWidth}=useProjectOverviewDefinitions();

    // 動きの定義
    const {onHiddenChangeClick,onHiddenListsChange}=useProjectOverviewActions(columnForHiddenLists,setColumnForHiddenLists,setAllHiddenLists);

    return(
        <Layout title={`${what}-${type}`}>

            <RoleLayout prefix={prefix}>

            <BasePageHeader {...{what,type,pageMinWidth,pageMaxWidth,"subtitle":"案件一覧"}}/>

            <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth} />

           <BaseTable tableTheme={`案件データ一覧`} thSets={Object.fromEntries(["案件名","開始日","終了日","割当済町目数","配布済町目数","設定部数","現在配布部数","編集"].map(title=>[title,title]))} maxWidth={pageMaxWidth} minWidth={pageMinWidth} mb={"mb-3"} sortSets={onHiddenChangeClick}>
            {projectData.map(function(projectSets,index){
            //  trの中身
             return <TrInner key={index} {...{projectSets}} />
            })}
            </BaseTable>

            {/* リンク */}
            <div className="mt-4">
                <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.logout`}  what="ログアウト"/>
            </div>

            <p>　</p>

            {/* sort */}
            <HiddenList columnForHiddenLists={columnForHiddenLists} onHiddenListsChange={onHiddenListsChange} />

            </RoleLayout>
        </Layout>
    )
}
