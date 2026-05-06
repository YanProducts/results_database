import useManagementDataActions from "../../Action/Clerical/useManagementDataActions";
import BasePageHeader from "../../Components/Common/BasePageHeader";
import BaseTable from "../../Components/Common/BaseTable";

import useManagementDataDefinitions from "../../Definition/Clerical/useManagementDataDefinitions";
import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";

// 入力担当が現時点で記録されているデータを確認し、その後エクスポートか自分で記録追加かを決める
export default function ManagementData({prefix,what,type,projectInSql}){

    console.log(projectInSql)

    // 定義
     const { data, setData, post, processing, errors,clearErrors, reset,pageMaxWidth,pageMinWidth}=useManagementDataDefinitions();

    // 動き
    const {}=useManagementDataActions();

    return(
        <Layout title={`${what}-${type}`}>
        <RoleLayout prefix={prefix}>
            <BaseTable tableTheme={`案件データ一覧`} thSets={["プロジェクト名","割当後データの締切","割当済の町目数","配布後の町目数","配布合計部数"].map(title=>({[title]:title}))} pageMaxWidth={pageMaxWidth} pageMinWidth={pageMinWidth}>
            {Object.entries(projectInSql).map(function(projectSets,index){
             const projectId=projectSets[0];
             const projectData=projectSets[1];
             return(
             <tr className="border border-black text-center">
                <td className="border border-black">{projectData.project_name}</td>
                <td className="border border-black">{projectData.end_date}</td>
                <td className="border border-black">{projectData.planned_town_counts}</td>
                <td className="border border-black">{projectData.recorded_town_counts}</td>
                <td className="border border-black">{projectData.recorded_distribution_counts}</td>

                <td className="border border-black"><label for={`check_${index}`}>一括出力</label><input id={`check_${index}`} type="checkbox" name="" value=""/></td>

                <td className="border border-black"><Link><span>編集</span></Link></td>

             </tr>
             )
            })}
            </BaseTable>

            <form onSubmit={""}>
                <p>チェックしたデータをCSV出力</p>
            </form>

            <div><p>割り当て最終日が〜のものはこちら</p></div>
            <div><p>入力を終えたデータの確認はこちら</p></div>

         </RoleLayout>
       </Layout>
    )
}
