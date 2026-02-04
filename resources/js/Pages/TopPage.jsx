import Layout from "../Layout/Layout";
import ThemeLists from "../Components/Part/topPage/ThemeLists";

// トップページ
export default function TopPage({}){

  return(
    <Layout title="トップ">
      <div className="custom_body bg-green-600">
      <p>　</p>
      <h1 className="base_h base_h1">データ共有-トップ-</h1>

      <div className="base_frame"><h2 className="base_h text-2xl mb-10">何を行いますか</h2></div>

      <ThemeLists href="" label="案件の登録"/>
      <ThemeLists href="staff/write_report" label="報告書の記入(スタッフ)"/>
      <ThemeLists href="clerical/write_report" label="報告書の記入(入力担当)"/>
      <ThemeLists href="branch_manager/assingment" label="町丁目データの確認"/>
      <ThemeLists href="clerical/export_report" label="報告書のエクスポート"/>
      <ThemeLists href="" label="発注書のエクスポート"/>
      <ThemeLists href="" label="スタッフ/事務担当/営業所/営業担当の登録"/>
      <ThemeLists href="" label="設定"/>

      </div>
    </Layout>
  )

}
