import useManagementDataActions from "../../Action/Clerical/useManagementDataActions";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";
import BaseTable from "../../Components/Common/BaseTable";
import ViewValidationErrors from "../../Components/Common/ViewValidationErrors";
import TrInner from "../../Components/Part/Clerical/ManagementData/TrInner";
import useManagementDataDefinitions from "../../Definition/Clerical/useManagementDataDefinitions";
import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";
import BaseButton from "../../Components/Common/BaseButton";
import BasePageHeader from "../../Components/Common/BasePageHeader";

// 入力担当が現時点で記録されているデータを確認し、その後エクスポートか自分で記録追加かを決める
export default function ManagementData({prefix,what,type,projectsInSql,archiveCutOffDate}){

    // 定義
     const { data, setData, post, processing, errors,clearErrors, reset,CSVOutputSets,setCSVOutputSets,isComplete,setIsComplete,pageMaxWidth,pageMinWidth}=useManagementDataDefinitions();

    // 動き
    const {onExportCheckChange,onCompleteCheckClick,onDecideExportData}=useManagementDataActions({post,data,setData,CSVOutputSets,setCSVOutputSets,isComplete,setIsComplete,projectsInSql});

    return(
        <Layout title={`${what}-${type}`}>
        <RoleLayout prefix={prefix}>

            <BasePageHeader {...{what,type,pageMinWidth,pageMaxWidth,"subtitle":"案件データ一覧"}}/>

            <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth} />

            <BaseTable tableTheme={`案件データ一覧`} thSets={Object.fromEntries(["案件名","割当後データの\n配布締切","割当済町目数","配布後町目数","配布合計部数","CSV出力","完成フラグ","編集"].map(title=>[title,title]))} maxWidth={pageMaxWidth} minWidth={pageMinWidth} mb={"mb-3"}>
            {Object.entries(projectsInSql).map(function(projectSets,index){
             const projectId=projectSets[0];
             const projectData=projectSets[1];
             const projectName=projectSets[1].project_name;
            //  trの中身
               return <TrInner key={index} {...{projectId,projectData,projectName,index,CSVOutputSets,onExportCheckChange,onCompleteCheckClick,isComplete}} />
            })}
            </BaseTable>

            {/* CSVエクスポート */}
            <form onSubmit={onDecideExportData}>
               <BaseButton minWidth={pageMinWidth} maxWidth={pageMaxWidth} processing={processing}/>
            </form>

            <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth} what={`割り当て最終日が${archiveCutOffDate}より前ものは`}routeName="clerical.check_archives"/>
            <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth} what={`入力を終えたデータの確認は`}routeName="clerical.check_archives"/>

            {/* リンク */}
            <div className="mt-4">
                <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth}  routeName={`${prefix}.logout`}  what="ログアウト"/>
            </div>

            <p>　</p>
         </RoleLayout>
       </Layout>
    )
}
