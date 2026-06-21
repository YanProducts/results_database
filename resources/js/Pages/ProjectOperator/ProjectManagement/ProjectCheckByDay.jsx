// 日毎の案件一覧
import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BasePageHeader from "../../../Components/Common/BasePageHeader";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";
import BaseTable from "../../../Components/Common/BaseTable";
import useProjectCheckByDayDefinitions from "../../../Definition/ProjectOperator/Management/useProjectCheckByDayDefinitions";
import useProjectCheckByDayActions from "../../../Action/ProjectOperator/Manegement/useProjectCheckByDayActions";
import ChoiceSort from "../../../Components/Part/ProjectOperator/ProjectManagement/ChoiceSort";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import TrInnerCheckedByDay from "../../../Components/Part/ProjectOperator/ProjectManagement/TrInnerInCheckByDay";

export default function ProjectCheckByDay({prefix,what,type,projectData}){

    // 定義
    const {data, setData, post, processing, errors,clearErrors, reset,checkByDayItems,flattedData,thWidthSets,pageMinWidth,pageMaxWidth}=useProjectCheckByDayDefinitions({projectData});

    // 動きの定義
    const {}=useProjectCheckByDayActions();

    console.log(flattedData)

    return(
        <Layout title={`${what}-${type}`}>

            <RoleLayout prefix={prefix}>

            <BasePageHeader {...{what,type,pageMinWidth,pageMaxWidth,"subtitle":"日ごとの案件一覧","mb":"mb-3"}}/>

            <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth} />

           {/* テーブルを何でsortするか */}
            {/* <ChoiceSort minWidth={pageMinWidth} maxWidth={pageMaxWidth}  /> */}

            {/* データの一覧 */}
             {/* 開始日付:[place_id:,place_name:,plan_contents:[案件名:何回目か:[終了日付&併配リスト&市のリスト]]] */}
             <BaseTable tableTheme="日毎の案件データ" thSets={Object.fromEntries([...Object.values(checkByDayItems),"編集"].map(title=>[title,title]))} thWidthSets={thWidthSets} maxWidth={pageMaxWidth} minWidth={pageMinWidth} mb={"mb-3"}>
                <TrInnerCheckedByDay {...{flattedData}}/>
            </BaseTable>

            {/* リンク */}
            <div className="mt-4">
                <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.project_overview`}  what="案件全体の確認"/>
                <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.dispatch_project`}  what="案件の登録"/>
                <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.logout`}  what="ログアウト"/>
            </div>

            <p>　</p>

            </RoleLayout>
        </Layout>
    )
}
