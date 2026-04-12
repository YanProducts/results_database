import Layout from "../Layout/Layout";
import ThemeLists from "../Components/Part/topPage/ThemeLists";
import BasePageHeader from "../Components/Common/BasePageHeader";

// トップページ
export default function TopPage({}){

  return(
    <Layout title="トップ">
      <div className="custom_body bg-green-600">
      <BasePageHeader what="データ共有" type="トップ" subtitle="何を行いますか"/>

      <ThemeLists routeName="project_operator.dispatch_project" label="案件を営業所へ"/>
      <ThemeLists routeName="branch_manager.assign_staff" label="案件をスタッフへ"/>
      <ThemeLists routeName="field_staff.write_report" label="報告書の記入(スタッフ)"/>
      <ThemeLists routeName="clerical.write_report" label="報告書の記入(入力担当)"/>
      <ThemeLists routeName="branch_manager.assign_staff" label="町丁目データの確認"/>
      <ThemeLists routeName="clerical.export_report" label="報告書のエクスポート"/>
      <ThemeLists routeName="clerical.export_purchase_order" label="発注書のエクスポート"/>
      <ThemeLists routeName="whole_data.provision" label="現場/入力/営業所/案件の各担当登録"/>
      <ThemeLists routeName="whole_data.admin_overview" routeParams={{"type":"all"}} label="現場/入力/営業所/案件の各担当確認/編集"/>
      <ThemeLists routeName="whole_data.register_places" label="営業所の登録"/>

      </div>
    </Layout>
  )

}
