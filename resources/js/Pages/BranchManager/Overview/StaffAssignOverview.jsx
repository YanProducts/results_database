import React from "react";
import Layout from "../../../Layout/Layout";
import { RoleLayout } from "../../../Layout/RoleLayout";
import BaseLinkLine from "../../../Components/Common/BaseLinkLine";
import useStaffAssignOverviewDefinitions from "../../../Definition/BranchManager/Overview/useStaffAssignOverviewDefinitions";
import useStaffAssignOverviewActions from "../../../Action/BranchManager/Overview/useStaffAssignOverviewActions";
import checkStaffAssignOverviewProps from "../../../Support/ConfirmProps/BranchManager/Overview/checkStaffAssignOverviewProps";
import ViewValidationErrors from "../../../Components/Common/ViewValidationErrors";
import BasePageHeader from "../../../Components/Common/BasePageHeader";
import BaseTable from "../../../Components/Common/BaseTable";


export default function StaffAssignOverview({ prefix, what, type,allData, errors: errorFromRouter = {}}) {


    // propsの確認
    checkStaffAssignOverviewProps({allData})

    // 定義セット
    const {data, setData, post, processing, errors,clearErrors, reset,validationHidden,setValidationHidden,pageMinWidth,pageMaxWidth} = useStaffAssignOverviewDefinitions({});

    // 動き
    const {onEditBtnClick,onResetBtnClick} = useStaffAssignOverviewActions({data,setData,post,errors,errorFromRouter,clearErrors,setValidationHidden});

    return (
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>

                {/* ページ内容 */}
                <BasePageHeader {...{what,type,pageMinWidth,pageMaxWidth}}/>

                {/* バリデーションエラー */}
                <ViewValidationErrors errors={(errors && Object.keys(errors).length>0) ? errors : errorFromRouter} minWidth={pageMinWidth} maxWidth={pageMaxWidth}/>

                <BaseTable {...{allData,tableTheme:"割り当ての確認",thSets:{"date":"日付","project":"メイン案件","cities":"エリア","staffs":"スタッフ","button":"編集","reset":"リセット"},minWidth:pageMinWidth,maxWidth:pageMaxWidth}}>
                    {
                        // date=>日付の日本語=>[メイン案件名・市のリスト・スタッフリスト]、削除ボタン、編集ボタン
                        Object.entries(allData).map(function([date,dataInDate]){

                            // その日のメイン案件が何件あるか？(colspanに使用するため、案件が存在しない時でも1にする)
                            const mainProjectCounts=Object.keys(dataInDate?.project_sets).length || 0;

                            console.log(mainProjectCounts) //0も数多くある

                            return(
                                    (dataInDate?.project_sets && mainProjectCounts!==0) ?
                                    Object.entries(dataInDate.project_sets).map(([mainProjectName,dataInTheproject],index)=>
                                        <tr key={mainProjectName} className="">
                                            {index==0 &&
                                            <td className={`border-black border-2`}  rowSpan={mainProjectCounts}>{dataInDate?.dateInJpn || ""}</td> //日付
                                            }
                                            <td className={`border-black border-2`}>{mainProjectName ?? "-"}</td>
                                            <td className={`border-black border-2`}>{dataInTheproject?.cityNames ?? "-"}</td>
                                            <td className={`border-black border-2`}>{dataInTheproject?.staffs ?? "-"}</td>
                                           {index==0 &&
                                           <>
                                            <td rowSpan={mainProjectCounts} className={`border-black border-2`}><button className={`base_btn`} onClick={()=>{onEditBtnClick(date)}}>編集</button></td>
                                            <td rowSpan={mainProjectCounts} className={`border-black border-2`}><button className={"base_btn"} onClick={()=>{onResetBtnClick(date)}}>リセット</button></td>
                                           </>
                                           }
                                      </tr>
                                    )
                                    :
                                      <tr>
                                         <td className={`border-black border-2`}>{dataInDate?.dateInJpn || ""}</td>
                                         <td className={`border-black border-2`}>-</td>
                                         <td className={`border-black border-2`}>-</td>
                                         <td className={`border-black border-2`}>-</td>
                                         <td className={`border-black border-2`}>-</td>
                                      </tr>
                                )
                        }
                        )

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
