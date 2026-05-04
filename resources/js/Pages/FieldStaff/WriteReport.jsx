import useWriteReportActions from "../../Action/FieldStaffs/useWriteReportActions";
import useWriteReportDefinitions from "../../Definition/FieldStaffs/useWriteReportDefinitions";
import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";
import InputPageHeader
 from "../../Components/Common/InputPageHeader";
import SelectPartsForViewChange from "../../Components/Common/SelectPartsForViewChange";
import BaseTable from "../../Components/Common/BaseTable";

export default function WriteReport({what,type,prefix,staff,dateSets,assignDataToStaff}){

    const {data, setData, post, processing, errors,clearErrors, reset,selectedDate,setSelectedDate,pageMaxWidth,pageMinWidth}=useWriteReportDefinitions();
    const {onSelectedDateChange,onAssignedInputChange,onSubmitBtnClick}=useWriteReportActions({selectedDate,setSelectedDate,setData,post});

    return(
    <Layout title={`${what}-${type}`}>
     <RoleLayout prefix={prefix}>
        <>
        {/* タイトル */}
        <InputPageHeader what={what} type={type} minWidth={pageMinWidth} maxWidth={pageMaxWidth} inputWhat="以下"/>
        {/* 投稿フォーム */}
        <form onSubmit={onSubmitBtnClick} className={`${pageMinWidth} ${pageMaxWidth} mx-auto`}>
            {/* 日付の選択 */}
            <SelectPartsForViewChange value={selectedDate} onChange={onSelectedDateChange} prefix={"日付："} keyValueSets={dateSets} disabled={assignDataToStaff[selectedDate] ? true :false} fixed={assignDataToStaff[selectedDate] ? true :false} fixContents={assignDataToStaff[selectedDate] ? new Date(selectedDate).toLocaleDateString("ja-JP", {month: "long",day: "numeric"}) : ""}/>


            {/* 報告書の入力 */}
            {selectedDate &&  (assignDataToStaff[selectedDate] ?

            Object.entries(assignDataToStaff[selectedDate]).map(keyValueSets=>
                <>
                <div className={`base_frame base_backColor text-center font-bold text-lg ${pageMinWidth} ${pageMinWidth} mb-2`}>{keyValueSets[0]}</div>
                <BaseTable thSets={{0:"町名",...keyValueSets[1]["project_set"]}} allData={[]}>
                    {Object.values(keyValueSets[1]["each_data"]).map((eachData,tdIndex)=>
                    <tr className="border-black border-2 base_backColor" key={tdIndex}>
                        <td className="border-black border-2">{eachData.townName}</td>
                        <td className="border-black border-2">{eachData.household}</td>

                        {/* 併配要定義 */}
                        {/* 併配の定義によって数を変化 */}


                        <td className="border-black border-2">
                            <input onChange={(e)=>onAssignedInputChange(e,eachData.planId)}/>
                        </td>
                    </tr>
                    )}
                </BaseTable>
            </>)
            :
              <div className={`text-center base_frame base_backColor ${pageMinWidth} ${pageMaxWidth} mb-3`}><p>案件は届いておりません</p></div>)
            }
        </form>

        </>

     </RoleLayout>
    </Layout>
    )
}
