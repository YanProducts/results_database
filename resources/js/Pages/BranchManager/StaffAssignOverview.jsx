import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";
import useStaffAssignOverviewDefinitions from "../../Definition/BranchManager/useStaffAssignOverviewDefinitions";
import useStaffAssignOverviewActions from "../../Action/BranchManager/useStaffAssignOverviewActions";
import checkStaffAssignOverviewProps from "../../Support/ConfirmProps/BranchManager/checkStaffAssignOverviewProps";
import { all } from "axios";
import BaseTable from "../../Components/Common/BaseTable";


export default function StaffAssignOverview({ prefix, what, type,allData }) {

    console.log(allData)

    // propsの確認
    checkStaffAssignOverviewProps({allData})

    // 定義セット
    const {data, setData, post, processing, errors,clearErrors, reset,validationHidden,setValidationHidden,pageMinWidth,pageMaxWidth} = useStaffAssignOverviewDefinitions({});

    // 動き
    const {} = useStaffAssignOverviewActions({data,setData,post,errors,clearErrors,setValidationHidden});

    return (
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>

                {/* ページ内容 */}
                <BasePageHeader {...{what,type,pageMinWidth,pageMaxWidth}}/>

                <BaseTable >
                    {
                        // date=>日付の日本語=>[メイン案件名・市のリスト・スタッフリスト]、削除ボタン、編集ボタン
                        Object.entries(allData.map(([date,dataInDate])=>
                            <></>
                        ))

                    }
                </BaseTable>

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
