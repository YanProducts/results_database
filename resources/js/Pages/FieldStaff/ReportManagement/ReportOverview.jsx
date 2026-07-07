import useReportOverviewAction from "../../../Action/FieldStaff/ReportManagement/useReportOverviewAction";
import BasePageHeader from "../../../Components/Common/BasePageHeader";
import OverviewTable from "../../../Components/Part/FieldStaff/OverviewTable";
import useReportOverviewDefinition from "../../../Definition/FieldStaff/ReportManagement/useReportOverviewDefinition";
import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";

// 複数日の報告書の一覧
export default function ReportOverview({prefix,what,type,staff,allData}){
    // assignDataToStaffキーのプロジェクト名はsameProjectFlagなども想定済みのもの
    const {pageMinWidth,pageMaxWidth}=useReportOverviewDefinition();

    const {}=useReportOverviewAction({});

    return(
    <Layout title={`${what}-${type}`}>
     <RoleLayout prefix={prefix}>

    {/* タイトル */}
    <BasePageHeader what={what} type={type} minWidth={pageMinWidth} maxWidth={pageMaxWidth} subtitle="詳細を見たい日付があればクリックしてください" needUserName={true} userName={staff} />

     <OverviewTable {...{allData}} />

    {/* リンク */}
      <div className="mt-1">
        <BaseLinkLine routeName={`${prefix}.write_report`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="報告書の投稿"/>
        <BaseLinkLine routeName={`${prefix}.write_report`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="以前の報告書の確認"/>
        <BaseLinkLine routeName={`${prefix}.logout`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="ログアウト"/>
      </div>

      <p>　</p>

    </RoleLayout>
    </Layout>
    )
}
