import Layout from "../Layout/Layout";
import ThemeLists from "../Components/Part/topPage/ThemeLists";

// トップページ
export default function TopPage({}){

  return(
    <Layout title="トップ">
      <div className="custom_body bg-green-600">
      <p>　</p>
      <h1 className="base_h base_h1 min-w-100">データ共有-トップ-</h1>

      <div className="base_frame min-w-100"><h2 className="base_h text-2xl mb-10 min-w-100">何を行いますか</h2></div>

      <ThemeLists routeName="project_operator.dispatch_project" label="案件の操作"/>
      <ThemeLists routeName="field_staff.write_report" label="報告書の記入(スタッフ)"/>
      <ThemeLists routeName="clerical.write_report" label="報告書の記入(入力担当)"/>
      <ThemeLists routeName="branch_manager.assignment" label="町丁目データの確認"/>
      <ThemeLists routeName="clerical.export_report" label="報告書のエクスポート"/>
      <ThemeLists routeName="clerical.export_purchase_order" label="発注書のエクスポート"/>
      <ThemeLists routeName="whole_data.provision" label="現場/入力/営業所/案件の各担当登録"/>
      <ThemeLists routeName="whole_data.admin_overview" routeParams={{"type":"all"}} label="現場/入力/営業所/案件の各担当確認/編集"/>
      <ThemeLists routeName="whole_data.register_places" label="営業所の登録"/>

      </div>
    </Layout>
  )

}
