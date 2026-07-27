import Layout from "../../Layout/Layout";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";
import { RoleLayout } from "../../Layout/RoleLayout";
import BasePageHeader from "../../Components/Common/BasePageHeader";
import ThemeLists from "../../Components/Part/topPage/ThemeLists";

// 営業所担当のトップページ
export default function TopPage({prefix,what,type}){

  return(
    <Layout title={`${what}-${type}`}>
     <RoleLayout prefix={prefix}>

        <BasePageHeader what={what} type={type} subtitle="何を行いますか"/>

        <ThemeLists routeName={`${prefix}.simple_assign_staff`} label="案件の割り当て(簡略版)"/>
        <ThemeLists routeName={`${prefix}.assign_staff`} label="案件の割り当て(詳細版)"/>
        <ThemeLists routeName={`${prefix}.assign_overview`} label="案件の確認と編集"/>
        <ThemeLists routeName={`${prefix}.choice_edit_report_target`} label="報告書の修正(未作成)"/>
        <ThemeLists routeName={`${prefix}.confirm_project_record`} label="町丁目配布データの確認"/>
        <ThemeLists routeName={`${prefix}.choice_report_target`} label="報告書の代替記入(未作成)"/>
        <ThemeLists routeName={`${prefix}.handing_assignment`} label="案件の代替投稿(未作成)"/>

    {/* リンク */}
      <div className="mt-4">
        <BaseLinkLine routeName={`${prefix}.logout`} what="ログアウト"/>
      </div>

     <p>　</p>

    </RoleLayout>
   </Layout>
  )
}

