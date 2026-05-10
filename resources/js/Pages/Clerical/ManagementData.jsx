import useManagementDataActions from "../../Action/Clerical/useManagementDataActions";
import BaseLinkLine from "../../Components/Common/BaseLinkLine";
import BaseTable from "../../Components/Common/BaseTable";
import ViewValidationErrors from "../../Components/Common/ViewValidationErrors";
import { Link } from "@inertiajs/react";

import useManagementDataDefinitions from "../../Definition/Clerical/useManagementDataDefinitions";
import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";
import BaseButton from "../../Components/Common/BaseButton";

// 入力担当が現時点で記録されているデータを確認し、その後エクスポートか自分で記録追加かを決める
export default function ManagementData({prefix,what,type,projectsInSql}){

    // 定義
     const { data, setData, post, processing, errors,clearErrors, reset,CSVOutputSets,setCSVOutputSets,isComplete,setIsComplete,pageMaxWidth,pageMinWidth}=useManagementDataDefinitions();

    // 動き
    const {onExportCheckChange,onCompleteCheckClick,onDecideExportData}=useManagementDataActions({post,data,setData,CSVOutputSets,setCSVOutputSets,isComplete,setIsComplete,projectsInSql});

    return(
        <Layout title={`${what}-${type}`}>
        <RoleLayout prefix={prefix}>
            <ViewValidationErrors errors={errors} minWidth={pageMinWidth} maxWidth={pageMaxWidth} />
            <BaseTable tableTheme={`案件データ一覧`} thSets={Object.fromEntries(["案件名","割当後データの締切","割当済の町目数","配布後の町目数","配布合計部数","完成したか","CSV出力"].map(title=>[title,title]))} pageMaxWidth={pageMaxWidth} pageMinWidth={pageMinWidth}>
            {Object.entries(projectsInSql).map(function(projectSets,index){
             const projectId=projectSets[0];
             const projectData=projectSets[1];
             const projectName=projectSets[1].project_name;
             return(
             <tr className="border border-black text-center" key={projectId}>
                <td className="border border-black">{projectName}</td>
                <td className="border border-black">{projectData.end_date}</td>
                <td className="border border-black">{projectData.planned_town_counts}</td>
                <td className="border border-black">{projectData.recorded_town_counts}</td>
                <td className="border border-black">{projectData.recorded_distribution_counts}</td>
                <td className="border border-black">{projectData.is_complete}</td>
                <td className="border border-black"><input id={`check_${index}`} type="checkbox" name="" checked={`${CSVOutputSets[projectName]?.isExport}`} onChange={(e)=>{onExportCheckChange(e,projectName,projectId)}}/><label for={`check_${index}`}>CSV出力</label></td>

                {/* 案件完成フラグボタン */}
                <td className="border border-black"><div className="base_btn_div"><button onClick={(e)=>{onCompleteclick(e,projectName,project_id)}}>{isComplete[projectName]?.completeFlag ? "編集可能" : "案件完成"}</button></div></td>

                <td className="border border-black"><Link className="cursor-pointer  text-blue-500 border-blue-500 border-b-2" href={route(`clerical.write_report`,{"edit_id":projectId})}><span>編集</span></Link></td>

             </tr>
             )
            })}
            </BaseTable>

            <form onSubmit={onDecideExportData}>
               <BaseButton />
            </form>

            <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth} what={`割り当て最終日が${"koko"}より前ものは`}routeName="clerical.check_archives"/>
            <BaseLinkLine minWidth={pageMinWidth} maxWidth={pageMaxWidth} what={`割り当て最終日が${"koko"}より前ものは`}routeName="clerical.check_archives"/>

            <div><p>入力を終えたデータの確認はこちら</p></div>

         </RoleLayout>
       </Layout>
    )
}
