import Layout from "../../../../Layout/Layout";
import { RoleLayout } from "../../../../Layout/RoleLayout";
import BasePageHeader from "../../../../Components/Common/BasePageHeader";
import BaseLinkLine from "../../../../Components/Common/BaseLinkLine";
import useDecideDateDefinitions from "../../../../Definition/BranchManager/ReportManagement/StaffToDate/useDecideDateDefinitions";
import useDecideDateActions from "../../../../Action/BranchManager/ReportManagement/StaffToDate/useDecideDateActions";
import OverviewTable from "../../../../Components/Part/FieldStaff/OverviewTable";
import ViewValidationErrors from "../../../../Components/Common/ViewValidationErrors";

// 複数日の報告書の一覧
export default function ReportOverview({prefix,what,type,userName,allData}){

    const {data,setData,post,processing, errors,clearErrors, reset, pageMinWidth,pageMaxWidth}=useDecideDateDefinitions();

    const {onDecideReport}=useDecideDateActions({data,setData,post});

    return(
    <Layout title={`${what}-${type}`}>
     <RoleLayout prefix={prefix}>

    {/* タイトル */}
    <BasePageHeader subtitle={`詳細を見たい日付があれば\nクリックしてください`} needUserName={true} {...{what,type,pageMinWidth,pageMaxWidth,userName}}/>

     {/* バリデーションエラー */}
    <ViewValidationErrors errors={errors} />

    {Object.entries(allData).map(([staffId,eachDataByStaff],index)=>
      <div className={`base_frame base_backColor border-black border rounded-sm ${pageMaxWidth} ${pageMinWidth} my-2`}  key={staffId}>
            <div className="text-lg font-bold border-black border-dashed border-b-0 py-1 my-2"><p className="text-center my-0">{eachDataByStaff.staff_name}</p></div>
            <OverviewTable {...{allData:eachDataByStaff.each_data_by_staff,pageMaxWidth,pageMinWidth,onDecideReport,staffId,processing}}/>
        </div>
     )}

    {/* リンク */}
      <div className={`mt-1`}>

        {/* 選び直し */}
        <BaseLinkLine routeName={`${prefix}.choice_report_target`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="スタッフの選び直し"/>

        {/* 営業所担当のトップへ */}
        <BaseLinkLine routeName={`${prefix}.top_page`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="営業所担当のトップ"/>

        <BaseLinkLine routeName={`${prefix}.logout`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="ログアウト"/>
      </div>

      <p>　</p>

    </RoleLayout>
    </Layout>
    )
}
