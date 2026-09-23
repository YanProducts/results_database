import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";
import useEditPlaceDefinitions from "../../Definition/WholeData/useEditPlaceDefinitions";
import useEditPlaceActions from "../../Action/WholeData/useEditPlaceActions";

export default function EditPlace({ prefix, what, type }) {

    // 定義セット
    const {} = useEditPlaceDefinitions({});

    // 動き
    const {} = useEditPlaceActions({});

    return (
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>

                {/* ページ内容 */}


                {/* リンク */}
                <div className="mt-1">
                    <BaseLinkLine
                        routeName={`${prefix}.top_page`}
                        what="トップ"
                    />
                    <BaseLinkLine
                        routeName={`${prefix}.logout`}
                        what="ログアウト"
                    />
                </div>

                <p>　</p>
            </RoleLayout>
        </Layout>
    );
}
