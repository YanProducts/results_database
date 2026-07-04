import useWriteReportActions from "../../Action/FieldStaffs/useWriteReportActions";
import useWriteReportDefinitions from "../../Definition/FieldStaffs/useWriteReportDefinitions";
import Layout from "../../Layout/Layout";
import { RoleLayout } from "../../Layout/RoleLayout";
import ReportDataInput from "../../Components/Part/FieldStaff/ReportDataInput";
import ReportConfirm from "../../Components/Part/FieldStaff/ReportConfirm";
import useWriteReportViewData from "../../Computed/useWriteReportViewData";

export default function WriteReport({what,type,prefix,staff,dateSets,assignDataToStaff,fromSimpleFlag}){

    // assignDataToStaffキーのプロジェクト名はsameProjectFlagなども想定済みのもの
    const {data, setData, post, processing, errors,clearErrors, reset,isConfirm,setIsConfirm,selectedDate,setSelectedDate,issuedCount,setIssuedCount,returnedCount,setReturnedCount,inputValues,setInputValues,inputRefs,pageMinWidth,pageMaxWidth}=useWriteReportDefinitions();

    const {onSelectedDateChange,onIssuedOrReturnedCountsChange,onAssignedInputChange,onSubmitBtnClick,onConfirmOkClick,onConfirmCancelClick}=useWriteReportActions({inputValues,setInputValues,inputRefs,selectedDate,setSelectedDate,setIsConfirm,setData,post});

    //テーブルのUIや変数などに必要な要素の取得(依存配列が変化しなければ再計算されない)
    const [tableSets,differenceExists]=useWriteReportViewData({assignDataToStaff,selectedDate,inputValues,issuedCount,returnedCount});

    return(
    <Layout title={`${what}-${type}`}>
     <RoleLayout prefix={prefix}>

        {/* 確認か入力か */}
        {!isConfirm ?
        <ReportDataInput {...{what,type,pageMinWidth,pageMaxWidth,staff,onSubmitBtnClick,selectedDate,onSelectedDateChange,issuedCount,returnedCount,onIssuedOrReturnedCountsChange,setIssuedCount,setReturnedCount,
        dateSets,assignDataToStaff,inputValues,inputRefs,onAssignedInputChange,tableSets,differenceExists,errors,processing,isConfirm,fromSimpleFlag}} />
        :
        <ReportConfirm {...{what,type,pageMaxWidth,pageMinWidth,data,assignDataToStaff,selectedDate,issuedCount,returnedCount,inputRefs,inputValues,onAssignedInputChange,onConfirmOkClick,onConfirmCancelClick,tableSets,errors,processing,isConfirm,fromSimpleFlag}}/>
        }
     </RoleLayout>
    </Layout>
    )
}
