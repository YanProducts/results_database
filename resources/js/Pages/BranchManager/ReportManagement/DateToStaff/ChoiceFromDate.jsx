// 報告書の確認を日付から選択する場合
import BasePageHeader from "../../../../Components/Common/BasePageHeader";
import ViewValidationErrors from "../../../../Components/Common/ViewValidationErrors";
import Layout from "../../../../Layout/Layout";
import { RoleLayout } from "../../../../Layout/RoleLayout";
import useChoiceFromDateActions from "../../../../Action/BranchManager/ReportManagement/DateToStaff/useChoiceFromDateActions";
import useChoiceFromDateDefinitions from "../../../../Definition/BranchManager/ReportManagement/DateToStaff/useChoiceFromDateDefinitions";

export default function ChoiceFromDate({what,type,prefix,staffs}){

    const {data,setData,post,processing, errors,clearErrors, reset, selectedStaffs,setSelectedStaffs,pageMinWidth,pageMaxWidth}=useChoiceFromDateDefinitions({});

    const {}=useChoiceFromDateActions({});

    return(
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>
                <BasePageHeader />
                {/* バリデーションエラー */}
                <ViewValidationErrors errors={errors} />
            </RoleLayout>
        </Layout>
    )
}
