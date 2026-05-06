import useManagementDataActions from "../../Action/Clerical/useManagementDataActions";
import BasePageHeader from "../../Components/Common/BasePageHeader";
import BaseTable from "../../Components/Common/BaseTable";

import useManagementDataDefinitions from "../../Definition/Clerical/useManagementDataDefinitions";
import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";

// 入力担当が現時点で記録されているデータを確認し、その後エクスポートか自分で記録追加かを決める
export default function ManagementData({what,type,projectInSql}){

    const []=useManagementDataDefinitions();
    const []=useManagementDataActions();

    return(
       <Layout>
         <RoleLayout>
            <BasePageHeader what={what} type={type} subtitle="" />
            <BaseTable />
         </RoleLayout>
       </Layout>
    )
}
