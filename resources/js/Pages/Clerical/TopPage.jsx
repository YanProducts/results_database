import Layout from "../../Layout/Layout";
import ThemeLists from "../../Components/Part/topPage/ThemeLists";
import BasePageHeader from "../../Components/Common/BasePageHeader";
import { RoleLayout } from "../../Layout/RoleLayout";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";

// トップページ
export default function TopPage({prefix,what,type}){

  return(
    <Layout title="トップ">
        <RoleLayout {...{prefix}}>

        <BasePageHeader what={what} type={type} subtitle="何を行いますか"/>

        <ThemeLists routeName="clerical.management_report" label="報告書データの操作/記入(入力担当)"/>
        <ThemeLists routeName="clerical.export_purchase_order" label="発注書のエクスポート"/>
        <ThemeLists routeName="clerical.export_purchase_order" label="担当の仕事の確認"/>
        <ThemeLists routeName="clerical.export_purchase_order" label="完了後の報告書の確認"/>

        <p>　</p>

        {/* リンク */}
        <div className="mt-4">
            <BaseLinkLine routeName={`${prefix}.logout`}  what="ログアウト"/>
        </div>

        </RoleLayout>
    </Layout>
  )

}
