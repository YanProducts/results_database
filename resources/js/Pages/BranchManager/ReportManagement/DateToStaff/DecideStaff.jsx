// 報告書の確認をスタッフから選択する場合
import BaseButton from "../../../../Components/Common/BaseButton";
import BasePageHeader from "../../../../Components/Common/BasePageHeader";
import ViewValidationErrors from "../../../../Components/Common/ViewValidationErrors";
import useDecideStaffDefinitions from "../../../../Definition/BranchManager/ReportManagement/DateToStaff/useDecideStaffDefinitions";
import useDecideStaffActions from "../../../../Action/BranchManager/ReportManagement/DateToStaff/useDecideStaffActions";
import Layout from "../../../../Layout/Layout";
import { RoleLayout } from "../../../../Layout/RoleLayout";
import BaseTable from "../../../../Components/Common/BaseTable";
import BaseLinkLine from "../../../../Components/Common/BaseLinkLine";
import { formatDateForView } from "../../../../Support/Common/formatDateForView";

export default function DecideDate({what,type,prefix,date,staffsInformationsInTheDate}){

    // staffsにはidとnameForUIが入っている
    const {data,setData,post,processing, errors,clearErrors, reset,pageMinWidth,pageMaxWidth}=useDecideStaffDefinitions({});

    const {onDecideReport}=useDecideStaffActions({date,data,setData,post});


    return(
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>
                <BasePageHeader {...{what,type,pageMinWidth,pageMaxWidth,"subtitle":"スタッフを選択してください"}}/>
                {/* バリデーションエラー */}
                <ViewValidationErrors errors={errors} />

                <div className={`base_frame ${pageMaxWidth} ${pageMinWidth} base_backColor`}>
                 <p>　</p>
                 <BaseTable {...{tableTheme:formatDateForView(date),minWidth:pageMinWidth,maxWidth:pageMaxWidth,thSets:{
                 "staff":"スタッフ","projects":"案件","towns":"エリア","status":"提出状況"},thWidthSets:["w-[25%]","w-[30%]","w-[35%]","w-[10%]"]}}>
                    {Object.values(staffsInformationsInTheDate).map(function(eachStaffData,index){
                        const isDataExists=(eachStaffData.projects).length>0;
                        return(
                        <tr key={index}  onClick={isDataExists ? ()=>onDecideReport(eachStaffData.staffId) : ()=>{}} className={`${isDataExists && "cursor-pointer hover:bg-amber-200"}`} >
                            <td className="border-black border-2">{eachStaffData.staff_name}</td>
                             {/* 住所と案件の例は最初のみ表示 */}
                             <td className="border-black border-2">{eachStaffData.projects.length>30 ? eachStaffData.projects.substring(0,30) + "..." : eachStaffData.projects}</td>
                             <td className="border-black border-2 text-left">{eachStaffData.towns.length>30 ? eachStaffData.towns.substring(0,30) + "..." : eachStaffData.towns}</td>
                             <td className="border-black border-2">{eachStaffData.status}</td>
                        </tr>
                        )
                        })}
                 </BaseTable>
                 <p>　</p>
                </div>

                <p>　</p>

                {/* リンク */}
                <div className="mt-1">
                        {/* 営業所担当のトップへ */}
                        <BaseLinkLine routeName={`${prefix}.top_page`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="営業所担当のトップ"/>

                        {/* 以前の報告書 */}

                        {/* ログアウト */}
                        <BaseLinkLine routeName={`${prefix}.logout`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="ログアウト"/>
                </div>
            </RoleLayout>
        </Layout>
    )
}
