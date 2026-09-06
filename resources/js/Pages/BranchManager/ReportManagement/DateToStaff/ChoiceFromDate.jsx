// 報告書の確認を日付から選択する場合
import BasePageHeader from "../../../../Components/Common/BasePageHeader";
import ViewValidationErrors from "../../../../Components/Common/ViewValidationErrors";
import Layout from "../../../../Layout/Layout";
import { RoleLayout } from "../../../../Layout/RoleLayout";
import useChoiceFromDateActions from "../../../../Action/BranchManager/ReportManagement/DateToStaff/useChoiceFromDateActions";
import useChoiceFromDateDefinitions from "../../../../Definition/BranchManager/ReportManagement/DateToStaff/useChoiceFromDateDefinitions";
import BaseTable from "../../../../Components/Common/BaseTable";
import BaseLinkLine from "../../../../Components/Common/BaseLinkLine";
import { formatDateForView } from "../../../../Support/Common/formatDateForView";

export default function ChoiceFromDate({what,type,prefix,dateStaffCalendar}){

    const {data,setData,post,processing, errors,clearErrors, reset, pageMinWidth,pageMaxWidth}=useChoiceFromDateDefinitions({});

    const {onDateClick}=useChoiceFromDateActions({data,setData,post});

    return(
        <Layout title={`${what}-${type}`}>
            <RoleLayout prefix={prefix}>
               <BasePageHeader {...{what,type,pageMinWidth,pageMaxWidth,"subtitle":"日付を選択してください"}}/>
                {/* バリデーションエラー */}
                <ViewValidationErrors errors={errors} />

                {/* dateStaffCalendarには日付=>[cityLists=>その時に行った市の名前(検索しやすいように市で取得)、complete=>[id=>名前],only_plan=>[id=>名前],only_report[id=>名前]]で取得 */}
                <BaseTable {...{tableTheme:"日毎の報告書提出状況",minWidth:pageMinWidth,maxWidth:pageMaxWidth,thSets:{"date":"日付","complete":"記入済","onlyAssign":"未記入","cityLists":"エリア(市)"},thWidthSets:["w-[25%]","w-[25%]","w-[25%]","w-[25%]"]}}>
                    {Object.entries(dateStaffCalendar).map(([date,eachData],index)=>
                        <tr key={index} onClick={()=>onDateClick(date)} className="cursor-pointer hover:bg-amber-200" >
                            {/* Y-m-dに表示側で直す */}
                            <td className="border-black border-2">{formatDateForView(date)}</td>
                            <td className="border-black border-2">{eachData.recorded.join("、")}</td>
                            <td className="border-black border-2">{eachData.only_assigned.join("、")}</td>
                            <td className="border-black border-2">{eachData.city_names.join("、")}</td>
                        </tr>
                    )}
                </BaseTable>

                <p>　</p>

                {/* リンク */}
                <div className="my-1">
                        {/* 営業所担当のトップへ */}
                        <BaseLinkLine routeName={`${prefix}.top_page`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="営業所担当のトップ"/>

                        {/* 以前の報告書 */}

                        {/* ログアウト */}
                        <BaseLinkLine routeName={`${prefix}.logout`} minWidth={pageMinWidth} maxWidth={pageMaxWidth} what="ログアウト"/>
                </div>

                <p>　</p>

            </RoleLayout>
        </Layout>
    )
}
