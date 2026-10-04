import useReportOverviewAction from "../../../Action/FieldStaff/ReportManagement/useReportOverviewAction";
import BasePageHeader from "../../../Components/Common/BasePageHeader";
import OverviewTable from "../../../Components/Part/FieldStaff/OverviewTable";
import useReportOverviewDefinition from "../../../Definition/FieldStaff/ReportManagement/useReportOverviewDefinition";
import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";


// 複数日の報告書の一覧
export default function ReportOverview({prefix,what,type,errors,userName,allData}){

    // assignDataToStaffキーのプロジェクト名はsameProjectFlagなども想定済みのもの
    const {validationHidden,setValidationHidden,pageMinWidth,pageMaxWidth}=useReportOverviewDefinition();

    const {onDecideReport}=useReportOverviewAction({errors,setValidationHidden});

    return(
    <Layout title={`${what}-${type}`}>
     <RoleLayout prefix={prefix}>

    {/* タイトル */}
    <BasePageHeader {...{what,type, pageMinWidth,pageMaxWidth, needUserName:true,userName}} />

    {/* 編集のgetがバリデーションで戻ってきたとき */}
    <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth} validationHidden={validationHidden} mt="mt-6"/>

     <OverviewTable {...{allData,pageMaxWidth,pageMinWidth,onDecideReport}} />

    {/* リンク */}
      <div className="mt-1">
        <BaseLinkLine routeName={`${prefix}.write_report`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="報告書の投稿"/>
        <BaseLinkLine routeName={`${prefix}.logout`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="ログアウト"/>
      </div>

      <p>　</p>

    </RoleLayout>
    </Layout>
    )
}
