import Layout from "../../../Layout/Layout"
import { RoleLayout } from "../../../Layout/RoleLayout"
import useProjectOverviewDefinitions from "../../../Definition/ProjectOperator/useProjectOverviewDefinitions";
import useProjectOverviewActions from "../../../Action/ProjectOperator/Overview/useProjectOverviewActions";
import BasePageHeader from "../../../Components/Common/BasePageHeader";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";
import BaseTable from "../../../Components/Common/BaseTable";
import TrInner from "../../../Components/Part/ProjectOperator/ProjectManagement/TrInner";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import HiddenList from "../../../Components/Part/ProjectOperator/ProjectManagement/HiddenList";
import ChoiceSort from "../../../Components/Part/ProjectOperator/ProjectManagement/ChoiceSort";

// 投稿したプロジェクトの一覧表示
export default function ProjectOverview({prefix,what,type,projectData}){

    // 定義
    const {data, setData, post, processing, errors,clearErrors, reset,overViewItems, selectedSort,setSelectedSort,prioritySort,setPrioritySort,selectedAscOrDes,setSelectedAscOrDes,ascOrDes,setAscOrDes,sortItemIsVisible,setSortItemIsVisible,columnForHiddenLists,setColumnForHiddenLists,hiddenListVisible,setHiddenListsVisible,hiddenPatterns,allHiddenLists,setAllHiddenLists,showFullData,setShowFullData,pageMinWidth,pageMaxWidth}=useProjectOverviewDefinitions();

    // 動きの定義
    const {sortedProjectData,onSortChangeClick,onSortChangeClose,onAscOrDesClick,onSortKindChange,onSortChangeDecide,onHiddenChangeClick,onHiddenListsChange,onShowFullDataChange,onEachHiddenCloseClick}=useProjectOverviewActions({projectData,overViewItems,setSortItemIsVisible,selectedSort,setSelectedSort,prioritySort,setPrioritySort,selectedAscOrDes,setSelectedAscOrDes,ascOrDes,setAscOrDes,columnForHiddenLists,setHiddenListsVisible,setColumnForHiddenLists,setAllHiddenLists,setShowFullData});


    return(
        <Layout title={`${what}-${type}`}>

            <RoleLayout prefix={prefix}>

            <BasePageHeader {...{what,type,pageMinWidth,pageMaxWidth,"subtitle":"案件一覧","mb":"mb-3"}}/>

            <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth} />

           {/* テーブルを何でsortするか */}
            <ChoiceSort minWidth={pageMinWidth} maxWidth={pageMaxWidth} {...{overViewItems,prioritySort,ascOrDes,onSortChangeClick,onSortKindChange,onSortChangeClose,sortItemIsVisible}} />

            {/* データの一覧 */}
           <BaseTable tableTheme={`案件データ一覧`} thSets={Object.fromEntries([...Object.values(overViewItems),"編集"].map(title=>[title,title]))} maxWidth={pageMaxWidth} minWidth={pageMinWidth} mb={"mb-3"}
           needSort={true} sortClick={onHiddenChangeClick}>
            {sortedProjectData.map(function(projectSets,index){
            //  trの中身
             return <TrInner key={index} {...{projectSets}} />
            })}
            </BaseTable>

            {/* リンク */}
            <div className="mt-4">
                <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.dispatch_project`}  what="案件の登録"/>
                <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.logout`}  what="ログアウト"/>
            </div>

            <p>　</p>

            {/* 何を表示する/しないかのリスト */}
            <HiddenList {...{columnForHiddenLists,onHiddenListsChange,hiddenListVisible,
            hiddenPatterns,allHiddenLists,showFullData,onShowFullDataChange,onEachHiddenCloseClick}}/>

            </RoleLayout>
        </Layout>
    )
}
