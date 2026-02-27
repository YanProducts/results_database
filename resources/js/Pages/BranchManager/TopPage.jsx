import Layout from "../../../Layout/Layout";
;
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";

// 営業所担当のトップページ
export default function TopPage({what,type}){


  return(
    <Layout title={`${what}-${type}`}>
     <div className="h-full min-h-screen bg-orange-200">

      <h1 className="base_h base_h1 min-w-100">{what}-{type}-</h1>

      <div className="base_frame min-w-100"><h2 className="base_h text-2xl mb-10 min-w-100">何を行いますか</h2></div>

      {/* <ThemeLists routeName="project_operator.dispatch_project" label="案件の操作"/>

      <ThemeLists routeName="field_staff.write_report" label="報告書の記入(スタッフ)"/>

      <ThemeLists routeName="clerical.write_report" label="報告書の記入(入力担当)"/> */}


    {/* リンク */}
      <div className="mt-4">
        <BaseLinkLine routeName="whole_data.logout"  what="ログアウト"/>
      </div>
    </div>
    </Layout>
  )
}

