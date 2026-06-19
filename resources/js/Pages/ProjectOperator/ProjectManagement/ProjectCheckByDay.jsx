// 日毎の案件一覧
import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BasePageHeader from "../../../Components/Common/BasePageHeader";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";
import BaseTable from "../../../Components/Common/BaseTable";
import useProjectCheckByDayDefinitions from "../../../Definition/ProjectOperator/Overview/useProjectCheckByDayDefinitions";
import useProjectCheckByDayActions from "../../../Action/ProjectOperator/Overview/useProjectCheckByDayActions";
import ChoiceSort from "../../../Components/Part/ProjectOperator/ProjectManagement/ChoiceSort";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";

export default function ProjectCheckByDay({prefix,what,type,projectData}){

    // 定義
    const {data, setData, post, processing, errors,clearErrors, reset,pageMinWidth,pageMaxWidth}=useProjectCheckByDayDefinitions();

    // 動きの定義
    const {}=useProjectCheckByDayActions({});


    return(
        <Layout title={`${what}-${type}`}>

            <RoleLayout prefix={prefix}>

            <BasePageHeader {...{what,type,pageMinWidth,pageMaxWidth,"subtitle":"日ごとの案件一覧","mb":"mb-3"}}/>

            <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth} />

           {/* テーブルを何でsortするか */}
            {/* <ChoiceSort minWidth={pageMinWidth} maxWidth={pageMaxWidth}  /> */}

            {/* データの一覧 */}
             {/* 開始日付:[営業所:[案件名:[終了日付&併配リスト&市のリスト]]] */}


            {/* リンク */}
            <div className="mt-4">
                <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.dispatch_project`}  what="案件の登録"/>
                <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.logout`}  what="ログアウト"/>
            </div>

            <p>　</p>

            </RoleLayout>
        </Layout>
    )
}
